<?php

namespace App\Factory;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\RecurringInvoice;

/**
 * Class RecurringInvoiceToInvoiceFactory
 * @package App\Factory
 */
class RecurringInvoiceToInvoiceFactory
{
    /**
     * @param RecurringInvoice $recurring_invoice
     * @param \App\Models\Customer $customer
     * @return \App\Models\Invoice
     */
    public static function create(RecurringInvoice $recurring_invoice, Customer $customer): Invoice
    {
        $invoice = new Invoice();
        $invoice->setAccount($recurring_invoice->account);
        $invoice->setStatus(Invoice::STATUS_DRAFT);
        $invoice->setCustomer($recurring_invoice->customer);
        $invoice->setDueDate();
        $invoice->setTotal($recurring_invoice->total);
        $invoice->setBalance($recurring_invoice->total);
        $invoice->setUser($recurring_invoice->user);

        $invoice->sub_total = $recurring_invoice->sub_total;
        $invoice->tax_total = $recurring_invoice->tax_total;
        $invoice->discount_total = $recurring_invoice->discount_total;
        $invoice->tax_rate = $recurring_invoice->tax_rate;
        $invoice->is_amount_discount = $recurring_invoice->is_amount_discount;
        $invoice->po_number = $recurring_invoice->po_number;
        $invoice->footer = $recurring_invoice->footer;
        $invoice->terms = $recurring_invoice->terms;
        $invoice->public_notes = $recurring_invoice->public_notes;
        $invoice->private_notes = $recurring_invoice->private_notes;
        $invoice->date = date_create()->format('Y-m-d');
        $invoice->is_deleted = false;
        $invoice->line_items = $recurring_invoice->line_items;
        $invoice->recurring_id = $recurring_invoice->id;

        return $invoice;
    }
}
