<?php

namespace App\Factory;

use App\Invoice;
use App\User;
use App\Account;
use Carbon\Carbon;

class CloneInvoiceFactory
{
    /**
     * @param Invoice $invoice
     * @param User $user
     * @param Account $account
     * @return Invoice
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
