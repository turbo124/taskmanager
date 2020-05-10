<?php

namespace App\Services\Invoice;

use App\Invoice;
use App\Payment;
use App\Services\Customer\CustomerService;

class ApplyPayment
{

    private Invoice $invoice;
    private Payment $payment;
    private $payment_amount;

    public function __construct(Invoice $invoice, Payment $payment, $payment_amount)
    {
        $this->invoice = $invoice;
        $this->payment = $payment;
        $this->payment_amount = $payment_amount;
    }

    public function run()
    {
        $this->payment->ledger()->updateBalance($this->payment_amount * -1);

        $this->updateCustomer();

        $this->updateInvoiceTotal();

        if ($this->invoice->partial && $this->invoice->partial > 0) {
            //is partial and amount is exactly the partial amount
            return $this->adjustInvoiceBalance();
        }

        if ($this->payment_amount > $this->invoice->balance) {
            return $this->invoice;
        }

        $this->invoice->resetPartialInvoice($this->payment_amount * -1, 0, $this->payment_amount == $this->invoice->balance);
        return $this->invoice;
    }

    private function updateCustomer()
    {
              $this->payment->customer->setBalance($this->payment_amount * -1);
        $this->payment->customer->save();
    }

    private function adjustInvoiceBalance()
    {
        $balance_adjustment = $this->invoice->partial > $this->payment_amount ? $this->payment_amount : $this->invoice->partial;
        $balance_adjustment = $this->invoice->partial == $this->payment_amount ? 0 : $balance_adjustment;
        $this->invoice->resetPartialInvoice($this->payment_amount * -1, $balance_adjustment);
        return $this->invoice;
    }

    private function updateInvoiceTotal()
    {
        $invoice = $this->payment->invoices->where('id', $this->invoice->id)->first();
        $invoice->pivot->amount = $this->payment_amount;
        $invoice->pivot->save();
    }
}
