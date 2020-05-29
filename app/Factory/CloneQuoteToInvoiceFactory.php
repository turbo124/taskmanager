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
        $invoice->account_id = $account->id;
        $invoice->customer_id = $quote->customer_id;
        $invoice->user_id = $user->id;
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
        $invoice->total = $quote->total;
        $invoice->balance = $quote->balance;
        $invoice->partial = $quote->partial;
        $invoice->partial_due_date = $quote->partial_due_date;
        $invoice->last_viewed = $quote->last_viewed;
        $invoice->status_id = Invoice::STATUS_DRAFT;
        $invoice->number = '';
        $invoice->balance = $quote->total;
        $invoice->line_items = $quote->line_items;
        $invoice->due_date = !empty($quote->customer->getSetting('payment_terms')) ? Carbon::now()->addDays(
            $quote->customer->getSetting('payment_terms')
        )->format('Y-m-d H:i:s') : $quote->due_date;
        return $invoice;
    }
}
