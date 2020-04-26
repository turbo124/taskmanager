<?php

namespace App\Factory;

use App\RecurringInvoice;

class RecurringInvoiceFactory
{
    public static function create(int $customer_id, int $account_id, $total): RecurringInvoice
    {
        $invoice = new RecurringInvoice();
        $invoice->account_id = $account_id;
        $invoice->customer_id = $customer_id;
        $invoice->status_id = RecurringInvoice::STATUS_DRAFT;
        $invoice->total = $total;
        $invoice->balance = $total;
        $invoice->user_id = auth()->user()->id;
        $invoice->frequency_id = RecurringInvoice::FREQUENCY_MONTHLY;
     
        return $invoice;
    }
}
