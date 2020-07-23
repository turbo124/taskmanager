<?php

namespace App\Factory;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Account;
use Carbon\Carbon;

class CloneInvoiceFactory
{
    /**
     * @param \App\Models\Invoice $invoice
     * @param User $user
     * @param Account $account
     * @return \App\Models\Invoice
     */
    public static function create(Invoice $invoice, User $user, Account $account)
    {
        $clone_invoice = $invoice->replicate();
        $clone_invoice->setAccount($account);
        $clone_invoice->setStatus(Invoice::STATUS_DRAFT);
        $clone_invoice->setNumber();
        $clone_invoice->setUser($user);
        $clone_invoice->setBalance($invoice->total);
        $clone_invoice->setDueDate();

        return $clone_invoice;
    }
}
