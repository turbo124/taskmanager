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
        $clone_invoice->number = null;
        $clone_invoice->partial_due_date = null;
        $clone_invoice->setUser($user);
        $clone_invoice->setBalance($invoice->total);
        $clone_invoice->due_date = !empty($invoice->customer->getSetting('payment_terms')) ? Carbon::now()->addDays(
            $invoice->customer->getSetting('payment_terms')
        )->format('Y-m-d H:i:s') : $invoice->due_date;

        return $clone_invoice;
    }
}
