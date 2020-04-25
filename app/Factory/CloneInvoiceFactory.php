<?php

namespace App\Factory;

use App\Invoice;

class CloneInvoiceFactory
{
    public static function create($invoice, $user_id, $account_id)
    {
        $clone_invoice = new Invoice();
        $clone_invoice->account_id = $account_id;
        $clone_invoice->status_id = Invoice::STATUS_DRAFT;
        $clone_invoice->number = null;
        $clone_invoice->date = $invoice->date;
        $clone_invoice->due_date = $invoice->due_date;
        $clone_invoice->partial_due_date = null;
        $clone_invoice->user_id = $user_id;
        $clone_invoice->total = $invoice->total;
        $clone_invoice->balance = $invoice->total;
        $clone_invoice->line_items = $invoice->line_items;

        return $clone_invoice;
    }
}
