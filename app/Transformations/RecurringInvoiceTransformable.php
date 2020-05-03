<?php

namespace App\Transformations;

use App\Invoice;
use App\RecurringInvoice;
use App\Repositories\CustomerRepository;
use App\Customer;

trait RecurringInvoiceTransformable
{

    /**
     * Transform the invoice
     *
     * @param Invoice $invoice
     * @return Invoice
     */
    protected function transformInvoice(RecurringInvoice $invoice)
    {

        return [
        'id' => (int)$invoice->id,
        'number' => $invoice->number,
        'customer_id' => $invoice->customer_id,
        'date' => $invoice->date,
        'due_date' => $invoice->due_date,
        'start_date' => $invoice->start_date,
        'total' => $invoice->total,
        'sub_total' => $invoice->sub_total,
        'tax_total' => $invoice->tax_total,
        'discount_total' => $invoice->discount_total,
        'deleted_at' => $invoice->deleted_at,
        'created_at' => $invoice->created_at,
        'status_id' => $invoice->status_id,
        'public_notes' => $invoice->public_notes ?: '',
        'private_notes' => $invoice->private_notes ?: '',
        'terms' => $invoice->terms,
        'footer' => $invoice->footer,
        'line_items' => $invoice->line_items,
        'custom_value1' => $invoice->custom_value1 ?: '',
        'custom_value2' => $invoice->custom_value2 ?: '',
        'custom_value3' => $invoice->custom_value3 ?: '',
        'custom_value4' => $invoice->custom_value4 ?: '',

    ];
    }

}
