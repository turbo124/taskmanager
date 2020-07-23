<?php

namespace App\Services\Invoice;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Customer\CustomerService;
use Illuminate\Support\Carbon;

/**
 * Class MakeInvoicePayment
 * @package App\Services\Invoice
 */
class MakeInvoicePayment
{

    /**
     * @var \App\Models\Invoice
     */
    private Invoice $invoice;

    /**
     * @var \App\Models\Payment
     */
    private Payment $payment;
    private $payment_amount;

    public function __construct(Invoice $invoice, Payment $payment, $payment_amount)
    {
        $this->invoice = $invoice;
        $this->payment = $payment;
        $this->payment_amount = $payment_amount;
    }

    public function execute()
    {
        $this->updateCustomer();

        $this->payment->transaction_service()->createTransaction(
            $this->payment_amount * -1,
            $this->payment->customer->balance
        );

        $this->updateInvoiceTotal();

        if ($this->invoice->partial && $this->invoice->partial > 0) {
            //is partial and amount is exactly the partial amount
            return $this->updateInvoice();
        }

        if ($this->payment_amount > $this->invoice->balance) {
            return $this->invoice;
        }

        $this->invoice->reduceBalance($this->payment_amount);

        return $this->invoice;
    }

    /**
     * @return bool
     */
    private function updateCustomer(): bool
    {
        $this->payment->customer->reduceBalance($this->payment_amount);
        $this->payment->customer->save();
        return true;
    }

    /**
     * @return Invoice
     */
    private function updateInvoice(): Invoice
    {
        $this->resetPartialInvoice();
        $this->updateBalance($this->payment_amount);
        $this->setStatus();
        $this->setDueDate();
        $this->save();

        return $this->invoice;
    }

    private function updateBalance(float $amount)
    {
        $this->invoice->reduceBalance($amount);
    }

    private function setStatus()
    {
        $this->invoice->setStatus(Invoice::STATUS_PARTIAL);
    }

    private function setDueDate()
    {
        $this->invoice->setDueDate();
    }

    private function save()
    {
        $this->invoice->save();
    }

    /**
     * @return \App\Models\Invoice
     */
    private function updateInvoiceTotal(): Invoice
    {
        $invoice = $this->payment->invoices->where('id', $this->invoice->id)->first();
        $invoice->pivot->amount = $this->payment_amount;
        $invoice->pivot->save();
        return $invoice;
    }

    /**
     * @return bool
     */
    private function resetPartialInvoice(): bool
    {
        $balance_adjustment = $this->invoice->partial > $this->payment_amount ? $this->payment_amount : $this->invoice->partial;
        $balance_adjustment = $this->invoice->partial == $this->payment_amount ? 0 : $balance_adjustment;

        if ($balance_adjustment > 0) {
            $this->invoice->partial -= $balance_adjustment;
            return true;
        }

        $this->invoice->partial = null;
        $this->invoice->partial_due_date = null;

        return true;
    }
}