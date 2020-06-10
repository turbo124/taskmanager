<?php

namespace App\Factory;

use App\Invoice;
use App\Order;
use App\Quote;
use App\User;
use App\Account;
use Carbon\Carbon;

/**
 * Class CloneOrderToQuoteFactory
 * @package App\Factory
 */
class CloneOrderToQuoteFactory
{
    /**
     * @param Order $order
     * @param User $user
     * @param Account $account
     * @return Quote|null
     */
    public static function create(Order $order, User $user, Account $account): ?Quote
    {
        $quote = new Quote;
        $quote->account_id = $quote->setAccount($account);
        $quote->customer_id = $quote->setCustomer($order->customer);
        $quote->order_id = $order->id;
        $quote->user_id = $quote->setUser($user);
        $quote->task_id = $order->task_id;
        $quote->discount_total = $order->discount_total;
        $quote->tax_total = $order->tax_total;
        $quote->is_amount_discount = $order->is_amount_discount;
        $quote->footer = $order->footer;
        $quote->tax_rate = $order->tax_rate;
        $quote->public_notes = $order->public_notes;
        $quote->private_notes = $order->private_notes;
        $quote->terms = $order->terms;
        $quote->sub_total = $order->sub_total;
        $quote->total = $quote->setTotal($order->total);
        $quote->partial = $order->partial;
        $quote->partial_due_date = $order->partial_due_date;
        $quote->last_viewed = $order->last_viewed;
        $quote->status_id = $quote->setStatus(Quote::STATUS_DRAFT);
        $quote->number = $quote->setNumber();
        $quote->date = $order->date;
        $quote->due_date = $quote->setDueDate();
        $quote->partial_due_date = null;
        $quote->balance = $quote->setBalance($order->total);
        $quote->line_items = $order->line_items;
        $quote->transaction_fee = $order->transaction_fee;
        $quote->shipping_cost = $order->shipping_cost;
        return $quote;
    }
}
