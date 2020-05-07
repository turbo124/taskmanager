<?php

namespace App\Factory;

use App\Invoice;
use App\Order;
use App\Quote;
use App\User;
use App\Account;

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
        $quote->account_id = $account->id;
        $quote->customer_id = $order->customer_id;
        $quote->order_id = $order->id;
        $quote->user_id = $user->id;
        $quote->task_id = $order->task_id;
        $quote->discount_total = $order->discount_total;
        $quote->tax_total = $order->tax_total;
        $quote->is_amount_discount = $order->is_amount_discount;
        $quote->footer = $order->footer;
        $quote->public_notes = $order->public_notes;
        $quote->private_notes = $order->private_notes;
        $quote->terms = $order->terms;
        $quote->sub_total = $order->sub_total;
        $quote->total = $order->total;
        $quote->balance = $order->balance;
        $quote->partial = $order->partial;
        $quote->partial_due_date = $order->partial_due_date;
        $quote->last_viewed = $order->last_viewed;
        $quote->status_id = Quote::STATUS_DRAFT;
        $quote->number = '';
        $quote->date = $order->date;
        $quote->due_date = $order->due_date;
        $quote->partial_due_date = null;
        $quote->balance = $order->total;
        $quote->line_items = $order->line_items;
        return $quote;
    }
}
