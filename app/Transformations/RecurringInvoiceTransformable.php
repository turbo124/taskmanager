<?php

namespace App\Transformations;

use App\Models\RecurringInvoice;

trait RecurringInvoiceTransformable
{

    /**
     * @param RecurringInvoice $invoice
     * @return array
     */
    protected function transformRecurringInvoice(RecurringInvoice $invoice)
    {
        return [
            'id'                  => (int)$invoice->id,
            'number'              => $invoice->number,
            'customer_id'         => $invoice->customer_id,
            'date'                => $invoice->date,
            'due_date'            => $invoice->due_date,
            'start_date'          => $invoice->start_date ?: '',
            'end_date'            => $invoice->end_date ?: '',
            'frequency'           => (int)$invoice->frequency,
            'total'               => $invoice->total,
            'sub_total'           => $invoice->sub_total,
            'tax_total'           => $invoice->tax_total,
            'discount_total'      => $invoice->discount_total,
            'currency_id'         => (int)$invoice->currency_id ?: null,
            'exchange_rate'       => (float)$invoice->exchange_rate,
            'deleted_at'          => $invoice->deleted_at,
            'created_at'          => $invoice->created_at,
            'status_id'           => $invoice->status_id,
            'public_notes'        => $invoice->public_notes ?: '',
            'private_notes'       => $invoice->private_notes ?: '',
            'terms'               => $invoice->terms,
            'footer'              => $invoice->footer,
            'line_items'          => $invoice->line_items,
            'custom_value1'       => $invoice->custom_value1 ?: '',
            'custom_value2'       => $invoice->custom_value2 ?: '',
            'custom_value3'       => $invoice->custom_value3 ?: '',
            'custom_value4'       => $invoice->custom_value4 ?: '',
            'transaction_fee'     => (float)$invoice->transaction_fee,
            'shipping_cost'       => (float)$invoice->shipping_cost,
            'gateway_fee'         => (float)$invoice->gateway_fee,
            'gateway_percentage'  => (bool)$invoice->gateway_percentage,
            'transaction_fee_tax' => (bool)$invoice->transaction_fee_tax,
            'shipping_cost_tax'   => (bool)$invoice->shipping_cost_tax,

        ];
    }

}
