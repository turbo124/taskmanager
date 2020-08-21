<?php

namespace App\Factory;

use App\Models\Invoice;
use App\Models\Quote;
use App\Models\User;

/**
 * Class CloneInvoiceToQuoteFactory
 * @package App\Factory
 */
class CloneInvoiceToQuoteFactory
{
    /**
     * @param Invoice $invoice
     * @param User $user
     * @return Quote|null
     */
    public static function create(Invoice $invoice, User $user): ?Quote
    {
        $quote = new Quote();
        $quote->setCustomer($invoice->customer);
        $quote->setUser($invoice->user);
        $quote->setAccount($invoice->account);
        $quote->setTotal($invoice->total);
        $quote->setStatus(Quote::STATUS_DRAFT);
        $quote->setNumber();
        $quote->setDueDate();
        $quote->setBalance($invoice->total);

        $quote->discount = 0;
        $quote->is_amount_discount = $invoice->is_amount_discount;
        $quote->po_number = $invoice->po_number;
        $quote->is_deleted = false;
        $quote->tax_rate_name = $invoice->tax_rate_name;
        $quote->tax_rate = $invoice->tax_rate;
        $quote->footer = $invoice->footer;
        $quote->public_notes = $invoice->public_notes;
        $quote->private_notes = $invoice->private_notes;
        $quote->terms = $invoice->terms;
        $quote->tax_total = $invoice->tax_total;
        $quote->sub_total = $invoice->sub_total;
        $quote->discount_total = $invoice->discount_total;
        $quote->custom_value1 = $invoice->custom_value1;
        $quote->custom_value2 = $invoice->custom_value2;
        $quote->custom_value3 = $invoice->custom_value3;
        $quote->custom_value4 = $invoice->custom_value4;
        $quote->partial = $invoice->partial;
        $quote->partial_due_date = $invoice->partial_due_date;
        $quote->last_viewed = $invoice->last_viewed;
        $quote->date = $invoice->date;
        $quote->line_items = $invoice->line_items;
        return $quote;
    }
}
