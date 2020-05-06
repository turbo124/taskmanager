<?php

namespace App\Services\Invoice;

use App\Invoice;
use App\Payment;
use App\Services\Customer\CustomerService;

class ApplyPayment
{

    private $invoice;
    private $payment;
    private $payment_amount;

    public function __construct($invoice, $payment, $payment_amount)
    {
        $this->invoice = $invoice;
        $this->payment = $payment;
        $this->payment_amount = $payment_amount;
    }

    public function run()
    {
        $this->payment->ledger()->updateBalance($this->payment_amount * -1);

        $this->payment->customer->setBalance($this->payment_amount * -1);
        $this->payment->customer->save();

        /* Update Pivot Record amount */
        foreach($this->payment->invoices as $invoice) {
            if ($invoice->id != $this->invoice->id) {
                continue;
            }
               
            $inv->pivot->amount = $this->payment_amount;
            $inv->pivot->save();
        }

        if ($this->invoice->partial && $this->invoice->partial > 0) {
            //is partial and amount is exactly the partial amount
            return $this->adjustInvoiceBalance();
        } 

        if($this->payment_amount > $this->invoice->balance) {
            return false;
        }

        $this->invoice->resetPartialInvoice($this->payment_amount * -1, 0, $this->payment_amount == $this->invoice->balance);
        return $this->invoice;
    }

    private function adjustInvoiceBalance()
    {
        $balance_adjustment = $this->invoice->partial > $this->payment_amount ? $this->payment_amount : $this->invoice->partial;
        $balance_adjustment = $this->invoice->partial == $this->payment_amount ? 0 : $balance_adjustment;
        $this->invoice->resetPartialInvoice($this->payment_amount * -1, $balance_adjustment);
        return $this->invoice;
    }
}
