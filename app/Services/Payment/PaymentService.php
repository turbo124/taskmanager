<?php

namespace App\Services\Payment;

use App\Factory\PaymentFactory;
use App\Invoice;
use App\Payment;
use App\Services\ServiceBase;

class PaymentService extends ServiceBase
{
    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function sendEmail()
    {
        return (new PaymentEmail($this->payment))->run();
    }

    public function reversePayment()
    {
        $invoices = $this->payment->invoices()->get();
        $customer = $this->payment->customer;

        $invoices->each(function ($invoice) {
            if ($invoice->pivot->amount > 0) {
                $invoice->status_id = Invoice::STATUS_SENT;
                $invoice->balance = $invoice->pivot->amount;
                $invoice->save();
            }
        });

        $this->payment->ledger()->updateBalance($this->payment->amount);

        $customer->service()->updateBalance($this->payment->amount)->updatePaidToDate($this->payment->amount * -1)
            ->save();
    }
}
