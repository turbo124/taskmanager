<?php

namespace App\Repositories;

use App\Account;
use App\Customer;
use App\Filters\PaymentFilter;
use App\Helpers\Currency\CurrencyConverter;
use App\Factory\PaymentFactory;
use App\NumberGenerator;
use App\Paymentable;
use App\Repositories\Base\BaseRepository;
use App\Payment;
use App\Credit;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Requests\SearchRequest;
use Exception;
use Illuminate\Support\Collection;
use App\Invoice;
use App\Events\Payment\PaymentWasCreated;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    /**
     * PaymentRepository constructor.
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        parent::__construct($payment);
        $this->model = $payment;
    }

    /**
     *  Return the payment
     * @param int $id
     * @return Payment
     */
    public function findPaymentById(int $id): Payment
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param SearchRequest $search_request
     * @param Account $account
     * @return array|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAll(SearchRequest $search_request, Account $account)
    {
        return (new PaymentFilter($this))->filter($search_request, $account->id);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function deletePayment()
    {
        return $this->delete();
    }

    public function getModel()
    {
        return $this->model;
    }

    public function processPayment(array $data, Payment $payment)
    {
        $payment = $this->save($data, $payment);

        $payment->customer->increasePaidToDateAmount($payment->amount);
        $payment->customer->save();

        $invoice_totals = isset($data['invoices']) && is_array($data['invoices']) ? array_sum(array_column($data['invoices'], 'amount')) : 0;
        $credit_totals = isset($data['credits']) && is_array($data['credits']) ? array_sum(array_column($data['credits'], 'amount')) : 0;

        $this->applyPaymentToInvoices($data, $payment);
        $this->applyPaymentToCredits($data, $payment);

        $invoice_totals -= $credit_totals;

        if ($invoice_totals == $payment->amount || $invoice_totals < $payment->amount) {
            $payment->applied += $invoice_totals;
        }

        $payment->save();

        return $payment;
    }


    /**
     * @param array $data
     * @param Payment $payment
     * @return Payment|null
     */
    public function save(array $data, Payment $payment): ?Payment
    {
        if (!empty($data)) {
            $payment->fill($data);
        }

        if (!$payment->id) {
            $payment = $this->convertCurrencies($payment);
        }

        $payment->status_id = Payment::STATUS_COMPLETED;

        $payment->save();

        if (!$payment->number || strlen($payment->number) == 0) {
            $payment->number = (new NumberGenerator)->getNextNumberForEntity($payment->customer, $payment);
            $payment->save();
        }

        $payment->ledger()->updateBalance($payment->amount * -1);

        event(new PaymentWasCreated($payment, $payment->account));

        return $payment->fresh();
    }

    private function convertCurrencies(Payment $payment)
    {
        $converted_amount = $objCurrencyConverter = (new CurrencyConverter())
            ->setAmount($payment->amount)
            ->setBaseCurrency($payment->account->getCurrency())
            ->setExchangeCurrency($payment->customer->currency)
            ->setDate($payment->date)
            ->calculate();

        if ($converted_amount) {
            $payment->exchange_rate = $converted_amount;
            $payment->currency_id = $payment->account->getCurrency()->id;
            $currency = $payment->customer->currency;
            $payment->exchange_currency_id = $payment->customer->currency;
        }

        return $payment;
    }

    private function applyPaymentToCredits(array $data, Payment $payment): bool
    {
        if (isset($data['credits']) && is_array($data['credits'])) {

            $credits = Credit::whereIn('id', array_column($data['credits'], 'credit_id'))->get();

            $data['credits'] = collect($data['credits'])->keyBy('credit_id')->toArray();

            foreach ($credits as $credit) {

                if (empty($data['credits'][$credit->id])) {

                    continue;
                }

                $payment->attachCredit($credit);
                $amount = $data['credits'][$credit->id]['amount'];
                $this->updateCredits($credit, $amount);
            }
        }

        return true;
    }

    /**
     * @param Credit $credit
     * @param Payment $payment
     * @param $amount
     */
    private function updateCredits(Credit $credit, $amount)
    {
        $credit_balance = $credit->balance;
        $status = $amount == $credit_balance ? Credit::STATUS_APPLIED : Credit::STATUS_PARTIAL;
        $credit->setStatus($status);
        $balance = floatval($amount * -1);
        $credit->setBalance($credit_balance + $balance);
        $credit->save();
    }

    private function applyPaymentToInvoices(array $data, Payment $payment): bool
    {

        if (isset($data['invoices']) && is_array($data['invoices'])) {

            $invoices = Invoice::whereIn('id', array_column($data['invoices'], 'invoice_id'))->get();

            $data['invoices'] = collect($data['invoices'])->keyBy('invoice_id')->toArray();

            foreach ($invoices as $invoice) {

                if (empty($data['invoices'][$invoice->id])) {

                    continue;
                }

                $payment->attachInvoice($invoice);

                $amount = $data['invoices'][$invoice->id]['amount'];

                $invoice = $invoice->service()->makeInvoicePayment($payment, $amount);
            }

            return true;
        }

        $this->adjustCustomerTotals($payment);

        return true;
    }

    private function adjustCustomerTotals(Payment $payment)
    {
        $payment->customer->increasePaidToDateAmount($payment->amount);
        $payment->customer->increaseBalance($payment->amount);
        $payment->customer->save();
    }

    public function reversePaymentsForInvoice(Invoice $invoice)
    {
        $total_paid = $invoice->total - $invoice->balance;

        $paymentables = Paymentable::wherePaymentableType(Invoice::class)
                                   ->wherePaymentableId($invoice->id)
                                   ->get();

        $paymentables->each(function ($paymentable) use ($total_paid) {

            $reversable_amount = $paymentable->amount - $paymentable->refunded;

            $total_paid -= $reversable_amount;

            $paymentable->amount = $paymentable->refunded;
            $paymentable->save();

        });

        return $total_paid;
    }
}
