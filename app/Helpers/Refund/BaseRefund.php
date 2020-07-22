<?php

namespace App\Helpers\Refund;

use App\Credit;
use App\Customer;
use App\Events\Payment\PaymentWasRefunded;
use App\Factory\CreditFactory;
use App\Helpers\InvoiceCalculator\LineItem;
use App\Invoice;
use App\Payment;
use App\Repositories\CreditRepository;

class BaseRefund
{

    /**
     * @var Customer
     */
    protected Customer $customer;

    /**
     * @var array
     */
    protected array $line_items;

    /**
     * @var Payment
     */
    protected Payment $payment;

    /**
     * @var float
     */
    private float $amount = 0;

    /**
     * @var bool
     */
    private bool $has_invoices = false;

    private CreditRepository $credit_repo;

    /**
     * @var array
     */
    protected array $data;

    /**
     * BaseRefund constructor.
     * @param Payment $payment
     * @param array $data
     * @param CreditRepository $credit_repo
     */
    public function __construct(Payment $payment, array $data, CreditRepository $credit_repo)
    {
        $this->payment = $payment;
        $this->data = $data;
        $this->credit_repo = $credit_repo;
    }

    protected function setCustomer()
    {
        $this->customer = $this->payment->customer;
    }

    private function setStatus()
    {
        $status = (float)abs($this->payment->refunded) === (float)$this->payment->amount ? Payment::STATUS_REFUNDED : Payment::STATUS_PARTIALLY_REFUNDED;

        $this->payment->setStatus($status);
    }

    private function reduceRefundTotal()
    {
        $this->payment->refunded -= $this->amount;
    }

    private function increaseRefundTotal()
    {
        $this->payment->refunded += $this->amount;
    }

    /**
     * @return mixed
     */
    private function updateCustomer()
    {
        $this->payment->customer->reducePaidToDateAmount($this->amount);
        $this->payment->customer->increaseBalance($this->amount);
        $this->payment->customer->save();
        return $this;
    }

    /**
     * @param float $amount
     * @param Invoice|null $invoice
     */
    protected function createLineItem(float $amount, Invoice $invoice = null)
    {
        if ($invoice !== null) {
            $this->has_invoices = true;
        }

        $this->line_items[] = (new LineItem($invoice))
            ->setQuantity(1)
            ->setUnitPrice($amount)
            ->setProductId('CREDIT')
            ->setNotes(
                !empty($invoice) ? 'REFUND for invoice number ' . $invoice->number : 'REFUND for transaction_reference ' . $this->payment->number
            )
            ->setSubTotal($amount)
            ->toObject();

        return $this;
    }

    /**
     * @param float $amount
     */
    protected function increaseRefundAmount(float $amount)
    {
        if (empty($amount)) {
            return $this;
        }

        $this->amount += $amount;
        return $this;
    }

    /**
     * @param float $amount
     */
    protected function reduceRefundAmount(float $amount)
    {
        if (empty($amount)) {
            return $this;
        }

        $this->amount -= $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return $this
     */
    private function createCreditNote()
    {
        $credit_note = CreditFactory::create($this->payment->account, $this->payment->user, $this->payment->customer);

        $credit_note = $this->credit_repo->createCreditNote(
            [
                'line_items' => $this->line_items,
                'total'      => $this->amount,
                'balance'    => $this->amount
            ],
            $credit_note
        );

        $credit_note->transaction_service()->createTransaction($this->amount, $credit_note->customer->balance);

        return $this;
    }

    protected function save()
    {
        $this->increaseRefundTotal();
        $this->setStatus();
        $this->updateCustomer();
        $this->createCreditNote();

        $this->payment->save();

        event(new PaymentWasRefunded($this->payment, $this->data));
    }

    protected function completeCreditRefund()
    {
        if(!empty($this->data['invoices'])) {
            return false;
        }

        $this->reduceRefundTotal();
        $this->setStatus();

        $this->payment->save();

        event(new PaymentWasRefunded($this->payment, $this->data));
    }
}
