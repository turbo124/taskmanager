<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Customer;
use App\Filters\PaymentFilter;
use App\Helpers\Currency\CurrencyConverter;
use App\Factory\PaymentFactory;
use App\Models\NumberGenerator;
use App\Models\Paymentable;
use App\Repositories\Base\BaseRepository;
use App\Models\Payment;
use App\Models\Credit;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Requests\SearchRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use App\Models\Invoice;
use App\Events\Payment\PaymentWasCreated;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    /**
     * PaymentRepository constructor.
     * @param \App\Models\Payment $payment
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

        $payment->transaction_service()->createTransaction($payment->amount * -1, $payment->customer->balance);

        if ($send_event) {
            event(new PaymentWasCreated($payment));
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
