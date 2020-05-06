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
        $this->payment->invoices->each(function ($inv) {
            if ($inv->id == $this->invoice->id) {
                $inv->pivot->amount = $this->payment_amount;
                $inv->pivot->save();
            }
        });

        if ($this->invoice->partial && $this->invoice->partial > 0) {
            //is partial and amount is exactly the partial amount
            if ($this->invoice->partial == $this->payment_amount) {
                $this->invoice->resetPartialInvoice($this->payment_amount * -1);
            } elseif ($this->invoice->partial > $this->payment_amount) { //partial amount exists, but the amount is less than the partial amount
                $this->invoice->resetPartialInvoice($this->payment_amount * -1, $this->payment_amount);
            } elseif ($this->invoice->partial < $this->payment_amount) { //partial exists and the amount paid is GREATER than the partial amount
                $this->invoice->resetPartialInvoice($this->payment_amount * -1, $this->invoice->partial);
            }
        } elseif ($this->payment_amount == $this->invoice->balance) { //total invoice paid.
            $this->invoice->resetPartialInvoice($this->payment_amount * -1, 0, true);
        } elseif ($this->payment_amount < $this->invoice->balance) { //partial invoice payment made
            $this->invoice->resetPartialInvoice($this->payment_amount * -1);
        }

        return $this->invoice;
    }
}
