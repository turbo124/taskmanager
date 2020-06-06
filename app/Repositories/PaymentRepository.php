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
     * @var float|int
     */
    private float $total_amount = 0;

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
        return (new PaymentFilter($this))->filter($search_request, $account);
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


    /**
     * @param array $data
     * @param Payment $payment
     * @return Payment|null
     */
    public function processPayment(array $data, Payment $payment)
    {
        $update_customer = !isset($payment->id);
        $payment = $this->save($data, $payment);

        $this->applyPaymentToInvoices($data, $payment);
        $this->applyPaymentToCredits($data, $payment);

        if ($update_customer) {
            // if there is no calculated amount from the invoices / credits use the amount specified in the payment
            $amount_redeemable = $this->total_amount == 0 ? $data['amount'] : $this->total_amount;
            $this->adjustCustomerTotals($payment->customer, $amount_redeemable);
        }

        if ($this->total_amount <= $payment->amount) {
            $payment->applyPayment($this->total_amount);
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
        $send_event = false;

        if (!empty($data)) {
            $payment->fill($data);
        }

        if (!$payment->id) {
            $payment = $this->convertCurrencies($payment);
            $send_event = true;
        }

        $payment->setNumber();
        $payment->setStatus(payment::STATUS_COMPLETED);
        $payment->save();

        $payment->transaction_service()->createTransaction($payment->amount * -1);

        if ($send_event) {
            event(new PaymentWasCreated($payment, $payment->account));
        }

        return $payment->fresh();
    }

    /**
     * @param Payment $payment
     * @return Payment
     */
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
            $payment->exchange_currency_id = $payment->customer->currency;
        }

        return $payment;
    }

    /**
     * @param array $data
     * @param Payment $payment
     * @return bool
     */
    private function applyPaymentToCredits(array $data, Payment $payment): bool
    {
        if (empty($data['credits'])) {
            return true;
        }

        $credits = Credit::whereIn('id', array_column($data['credits'], 'credit_id'))->get();

        $data['credits'] = collect($data['credits'])->keyBy('credit_id')->toArray();

        foreach ($credits as $credit) {
            if (empty($data['credits'][$credit->id])) {
                continue;
            }

            $payment->attachCredit($credit);
            $amount = $data['credits'][$credit->id]['amount'];
            $this->updateCredits($credit, $amount);
            $this->total_amount -= $amount;
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

    /**
     * @param array $data
     * @param Payment $payment
     * @return bool
     */
    private function applyPaymentToInvoices(array $data, Payment $payment): bool
    {
        if (empty($data['invoices'])) {
            return true;
        }

        $invoices = Invoice::whereIn('id', array_column($data['invoices'], 'invoice_id'))->get();

        $data['invoices'] = collect($data['invoices'])->keyBy('invoice_id')->toArray();

        foreach ($invoices as $invoice) {
            if (empty($data['invoices'][$invoice->id])) {
                continue;
            }

            $payment->attachInvoice($invoice);

            $amount = $data['invoices'][$invoice->id]['amount'];
            $this->total_amount += $amount;

            $invoice->service()->makeInvoicePayment($payment, $amount);
        }

        return true;
    }

    /**
     * @param Customer $customer
     * @param float $amount
     */
    private function adjustCustomerTotals(Customer $customer, float $amount)
    {
        $customer->increasePaidToDateAmount($amount);
        //$payment->customer->increaseBalance($payment->amount);
        $customer->save();
    }

    public function reversePaymentsForInvoice(Invoice $invoice)
    {
        $total_paid = $invoice->total - $invoice->balance;

        $paymentables = Paymentable::wherePaymentableType(Invoice::class)
                                   ->wherePaymentableId($invoice->id)
                                   ->get();

        foreach ($paymentables as $paymentable) {
            $reversable_amount = $paymentable->amount - $paymentable->refunded;

            $total_paid -= $reversable_amount;

            $paymentable->amount = $paymentable->refunded;
            $paymentable->save();
        }

        return $total_paid;
    }
}
