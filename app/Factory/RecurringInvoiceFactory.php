<?php

namespace App\Factory;

use App\RecurringInvoice;

class RecurringInvoiceFactory
{
    public static function create(Customer $customer, Account $account, $total, User $user): RecurringInvoice
    {
        $invoice = new RecurringInvoice();
        $invoice->account_id = $account->id;
        $invoice->customer_id = $customer->id;
        $invoice->status_id = RecurringInvoice::STATUS_DRAFT;
        $invoice->total = $total;
        $invoice->balance = $total;
        $invoice->user_id = $user->id;
        $invoice->frequency_id = RecurringInvoice::FREQUENCY_MONTHLY;
     
        return $invoice;
    }
}
