<?php

namespace App\Factory;

use App\Customer;
use App\Invoice;
use App\Quote;
use App\RecurringInvoice;
use App\RecurringQuote;

/**
 * Class RecurringInvoiceToInvoiceFactory
 * @package App\Factory
 */
class RecurringQuoteToQuoteFactory
{
    /**
     * @param RecurringQuote $recurring_quote
     * @param Customer $customer
     * @return Quote
     */
    public static function create(RecurringQuote $recurring_quote, Customer $customer): Quote
    {
        $quote = new Quote();
        $quote->setAccount($recurring_quote->account);
        $quote->setStatus(Quote::STATUS_DRAFT);
        $quote->setCustomer($recurring_quote->customer);
        $quote->setDueDate();
        $quote->setTotal($recurring_quote->total);
        $quote->setBalance($recurring_quote->total);
        $quote->setUser($recurring_quote->user);

        $quote->sub_total = $recurring_quote->sub_total;
        $quote->tax_total = $recurring_quote->tax_total;
        $quote->discount_total = $recurring_quote->discount_total;
        $quote->tax_rate = $recurring_quote->tax_rate;
        $quote->is_amount_discount = $recurring_quote->is_amount_discount;
        $quote->po_number = $recurring_quote->po_number;
        $quote->footer = $recurring_quote->footer;
        $quote->terms = $recurring_quote->terms;
        $quote->public_notes = $recurring_quote->public_notes;
        $quote->private_notes = $recurring_quote->private_notes;
        $quote->date = date_create()->format('Y-m-d');
        $quote->is_deleted = false;
        $quote->line_items = $recurring_quote->line_items;
        $quote->recurring_id = $recurring_quote->id;

        return $quote;
    }
}
