<?php

namespace App\Services\Invoice;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Customer\CustomerService;

/**
 * Class MakeInvoicePayment
 * @package App\Services\Invoice
 */
class MakeInvoicePayment
{

    /**
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * @var Payment
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
        $amount_to_pay = $this->payment_amount;

        if($this->invoice->gateway_fee > 0) {
            $amount_to_pay += $this->invoice->gateway_fee;
        }

        $this->updateCustomer($amount_to_pay);
    
        $this->payment->transaction_service()->createTransaction(
           $amount_to_pay  * -1,
            $this->payment->customer->balance
        );

        $this->updateInvoiceTotal($amount_to_pay);

        if ($this->invoice->partial && $this->invoice->partial > 0) {
            //is partial and amount is exactly the partial amount
            return $this->updateInvoice($amount_to_pay, true);
        }

        if ($amount_to_pay > $this->invoice->balance) {
            return $this->invoice;
        }

        $this->updateInvoice($amount_to_pay);

        return $this->invoice;
    }

    /**
     * @return bool
     */
    private function updateCustomer($amount_to_pay): bool
    {
        $this->payment->customer->reduceBalance($amount_to_pay);
        $this->payment->customer->increasePaidToDate($amount_to_pay);
        $this->payment->customer->save();
        return true;
    }

    /**
     * @return Invoice
     */
    private function updateInvoice($amount_to_pay, $partial = false): Invoice
    {
        if ($partial) {
            $this->resetPartialInvoice($amount_to_pay);
            $this->setDueDate();
        }

        $this->updateBalance($amount_to_pay);
        $this->setStatus();
        $this->save();

        return $this->invoice;
    }

    private function updateBalance(float $amount)
    {
        $this->invoice->reduceBalance($amount);
    }

    private function setStatus()
    {
        $this->invoice->setStatus(
            $this->invoice->partial && $this->invoice->partial > 0 ? Invoice::STATUS_PARTIAL : Invoice::STATUS_PAID
        );
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
     * @return Invoice
     */
    private function updateInvoiceTotal($amount_to_pay): Invoice
    {
        $invoice = $this->payment->invoices->where('id', $this->invoice->id)->first();
        $invoice->pivot->amount = $amount_to_pay;
        $invoice->pivot->save();
        return $invoice;
    }

    /**
     * @return bool
     */
    private function resetPartialInvoice($amount_to_pay): bool
    {
        $balance_adjustment = $this->invoice->partial > $amount_to_pay ? $amount_to_pay : $this->invoice->partial;
        $balance_adjustment = $this->invoice->partial == $amount_to_pay ? 0 : $balance_adjustment;

        if ($balance_adjustment > 0) {
            $this->invoice->partial -= $balance_adjustment;
            return true;
        }

        $this->invoice->partial = null;
        $this->invoice->partial_due_date = null;

        return true;
    }
}
