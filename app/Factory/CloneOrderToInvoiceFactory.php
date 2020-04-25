<?php

namespace App\Factory;

use App\Invoice;
use App\Order;
use App\Quote;

class CloneOrderToInvoiceFactory
{
    public static function create(Order $order, $user_id, $account_id): ?Invoice
    {
        $invoice = new Invoice();
        $invoice->account_id = $account_id;
        $invoice->customer_id = $order->customer_id;
        $invoice->user_id = $user_id;
        $invoice->order_id = $order->id;
        $invoice->task_id = $order->task_id;
        $invoice->discount_total = $order->discount_total;
        $invoice->tax_total = $order->tax_total;
        $invoice->is_amount_discount = $order->is_amount_discount;
        $invoice->footer = $order->footer;
        $invoice->public_notes = $order->public_notes;
        $invoice->private_notes = $order->private_notes;
        $invoice->terms = $order->terms;
        $invoice->sub_total = $order->sub_total;
        $invoice->total = $order->total;
        $invoice->balance = $order->balance;
        $invoice->partial = $order->partial;
        $invoice->partial_due_date = $order->partial_due_date;
        $invoice->last_viewed = $order->last_viewed;
        $invoice->status_id = Invoice::STATUS_DRAFT;
        $invoice->number = '';
        $invoice->date = $order->date;
        $invoice->due_date = $order->due_date;
        $invoice->partial_due_date = null;
        $invoice->balance = $order->total;
        $invoice->line_items = $order->line_items;

        return $invoice;
    }
}
