<?php

namespace App\Factory;

use App\Invoice;
use App\Quote;
use App\User;
use App\Account;
use Carbon\Carbon;

/**
 * Class CloneQuoteToInvoiceFactory
 * @package App\Factory
 */
class CloneQuoteToInvoiceFactory
{
    /**
     * @param Quote $quote
     * @param User $user
     * @param Account $account
     * @return Invoice|null
     */
    public static function create(Quote $quote, User $user, Account $account): ?Invoice
    {
        $invoice = new Invoice();
        $invoice->setAccount($account);
        $invoice->setCustomer($quote->customer);
        $invoice->setUser($user);
        $invoice->setTotal($quote->total);
        $invoice->setStatus(Invoice::STATUS_DRAFT);
        $invoice->setNumber();
        $invoice->setBalance($quote->total);
        $invoice->setDueDate();
        $invoice->discount_total = $quote->discount_total;
        $invoice->tax_total = $quote->tax_total;
        $invoice->is_amount_discount = $quote->is_amount_discount ?: false;
        $invoice->po_number = $quote->po_number;
        $invoice->footer = $quote->footer;
        $invoice->public_notes = $quote->public_notes;
        $invoice->private_notes = $quote->private_notes;
        $invoice->tax_rate = $quote->tax_rate;
        $invoice->terms = $quote->terms;
        $invoice->sub_total = $quote->sub_total ?: 0;
        $invoice->balance = $quote->balance;
        $invoice->partial = $quote->partial;
        $invoice->partial_due_date = $quote->partial_due_date;
        $invoice->last_viewed = $quote->last_viewed;
        $invoice->line_items = $quote->line_items;

        return $invoice;
    }
}
