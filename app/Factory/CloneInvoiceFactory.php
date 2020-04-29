<?php

namespace App\Factory;

use App\Invoice;
use App\User;
use App\Account;

class CloneInvoiceFactory
{
    public static function create(Invoice $invoice, User $user, Account $account)
    {
        $clone_invoice = $invoice->replicate();
        $clone_invoice->account_id = $account->id;
        $clone_invoice->status_id = Invoice::STATUS_DRAFT;
        $clone_invoice->number = null;
        $clone_invoice->partial_due_date = null;
        $clone_invoice->user_id = $user->id;
        $clone_invoice->balance = $invoice->total;

        return $clone_invoice;
    }
}
