<?php

namespace App\Factory;

use App\Invoice;
use App\Payment;
use App\RecurringInvoice;

class InvoiceToPaymentFactory
{
    public static function create(Invoice $invoice): Payment
    {
        $payment = new Payment;

        $payment->account_id = $invoice->account_id;
        $payment->user_id = $invoice->user_id;
        $payment->amount = $invoice->balance;
        $payment->applied = $invoice->balance;
        $payment->customer_id = $invoice->customer_id;
        $payment->transaction_reference = trans('texts.manual');

        return $payment;
    }
}
