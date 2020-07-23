<?php

namespace App\Factory;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Quote;
use App\Models\User;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Class CloneOrderToInvoiceFactory
 * @package App\Factory
 */
class CloneOrderToInvoiceFactory
{
    /**
     * @param \App\Models\Order $order
     * @param User $user
     * @param \App\Models\Account $account
     * @return Invoice|null
     */
    public static function create(Order $order, User $user, Account $account): ?Invoice
    {
        $invoice = new Invoice();
        $invoice->setAccount($account);
        $invoice->setCustomer($order->customer);
        $invoice->setUser($user);
        $invoice->setTotal($order->total);
        $invoice->setStatus(Invoice::STATUS_DRAFT);
        $invoice->setNumber();
        $invoice->setDueDate();
        $invoice->setBalance($order->total);

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
        $invoice->partial = $order->partial;
        $invoice->partial_due_date = $order->partial_due_date;
        $invoice->last_viewed = $order->last_viewed;
        $invoice->date = $order->date;
        $invoice->partial_due_date = null;
        $invoice->line_items = $order->line_items;
        $invoice->transaction_fee = $order->transaction_fee;
        $invoice->shipping_cost = $order->shipping_cost;
        $invoice->transaction_fee = $order->transaction_fee;
        $invoice->gateway_fee = $order->gateway_fee;
        $invoice->voucher_code = $order->voucher_code;
        $invoice->is_amount_discount = $order->is_amount_discount ?: false;

        Log::emergency('here ' . $order->gateway_fee);
        Log::emergency('here ' . $invoice->gateway_fee);

        return $invoice;
    }
}
