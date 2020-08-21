<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\Customer;
use App\Models\RecurringInvoice;
use App\Models\User;

class RecurringInvoiceFactory
{
    public static function create(Customer $customer, Account $account, User $user, $total): RecurringInvoice
    {
        $invoice = new RecurringInvoice();
        $invoice->account_id = $account->id;
        $invoice->customer_id = $customer->id;
        $invoice->status_id = RecurringInvoice::STATUS_DRAFT;
        $invoice->total = $total;
        $invoice->balance = $total;
        $invoice->user_id = $user->id;
        $invoice->frequency = 30;

        return $invoice;
    }
}
