<?php

namespace App\Factory;

use App\Invoice;
use App\Order;
use App\Quote;
use App\User;
use App\Account;
use Carbon\Carbon;

/**
 * Class CloneQuoteToOrderFactory
 * @package App\Factory
 */
class CloneQuoteToOrderFactory
{
    /**
     * @param Quote $quote
     * @param User $user
     * @param Account $account
     * @return Order|null
     */
    public static function create(Quote $quote, User $user, Account $account): ?Order
    {
        $order = new Order;
        $order->account_id = $account->id;
        $order->customer_id = $quote->customer_id;
        $order->quote_id = $quote->id;
        $order->user_id = $user->id;
        $order->task_id = $quote->task_id;
        $order->discount_total = $quote->discount_total;
        $order->tax_total = $quote->tax_total;
        $order->is_amount_discount = $quote->is_amount_discount;
        $order->footer = $quote->footer;
        $order->public_notes = $quote->public_notes;
        $order->private_notes = $quote->private_notes;
        $order->tax_rate = $quote->tax_rate;
        $order->terms = $quote->terms;
        $order->sub_total = $quote->sub_total;
        $order->total = $quote->total;
        $order->balance = $quote->balance;
        $order->partial = $quote->partial;
        $order->partial_due_date = $quote->partial_due_date;
        $order->status_id = Order::STATUS_DRAFT;
        $order->number = '';
        $order->date = $quote->date;
        $order->due_date = !empty($quote->customer->getSetting('payment_terms')) ? Carbon::now()->addDays(
            $quote->customer->getSetting('payment_terms')
        )->format('Y-m-d H:i:s') : $quote->due_date;
        $order->balance = $quote->total;
        $order->line_items = $quote->line_items;
        return $order;
    }
}
