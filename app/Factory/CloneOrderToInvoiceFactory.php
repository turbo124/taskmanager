<?php

namespace App\Factory;

use App\Invoice;
use App\Order;
use App\Quote;
use App\User;
use App\Account;
use Carbon\Carbon;

/**
 * Class CloneOrderToInvoiceFactory
 * @package App\Factory
 */
class CloneOrderToInvoiceFactory
{
    /**
     * @param Order $order
     * @param User $user
     * @param Account $account
     * @return Invoice|null
     */
    public static function create(Order $order, User $user, Account $account): ?Invoice
    {
        $invoice = new Invoice();
        $invoice->account_id = $account->id;
        $invoice->customer_id = $order->customer_id;
        $invoice->user_id = $user->id;
        $invoice->order_id = $order->id;
        $invoice->task_id = $order->task_id;
        $invoice->discount_total = $order->discount_total;
        $invoice->tax_rate = $order->tax_rate;
        $invoice->tax_total = $order->tax_total;
        $invoice->is_amount_discount = $order->is_amount_discount;
        $invoice->footer = $order->footer;
        $invoice->public_notes = $order->public_notes;
        $invoice->private_notes = $order->private_notes;
        $invoice->terms = $order->terms;
        $invoice->sub_total = $order->sub_total ?: 0;
        $invoice->total = $order->total;
        $invoice->balance = $order->balance;
        $invoice->partial = $order->partial;
        $invoice->partial_due_date = $order->partial_due_date;
        $invoice->last_viewed = $order->last_viewed;
        $invoice->status_id = Invoice::STATUS_DRAFT;
        $invoice->number = '';
        $invoice->date = $order->date;
        $invoice->due_date = !empty($order->customer->getSetting('payment_terms')) ? Carbon::now()->addDays(
            $order->customer->getSetting('payment_terms')
        )->format('Y-m-d H:i:s') : $order->due_date;
        $invoice->partial_due_date = null;
        $invoice->balance = $order->total;
        $invoice->line_items = $order->line_items;
        $invoice->custom_surcharge1 = $order->custom_surcharge1;
        $invoice->is_amount_discount = $order->is_amount_discount ?: false;

        return $invoice;
    }
}
