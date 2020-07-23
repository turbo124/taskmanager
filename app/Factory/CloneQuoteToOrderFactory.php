<?php

namespace App\Factory;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Quote;
use App\Models\User;
use App\Models\Account;
use Carbon\Carbon;

/**
 * Class CloneQuoteToOrderFactory
 * @package App\Factory
 */
class CloneQuoteToOrderFactory
{
    /**
     * @param \App\Models\Quote $quote
     * @param \App\Models\User $user
     * @param Account $account
     * @return Order|null
     */
    public static function create(Quote $quote, User $user, Account $account): ?Order
    {
        $order = new Order;
        $order->setAccount($account);
        $order->setCustomer($quote->customer);
        $order->setUser($user);
        $order->setTotal($quote->total);
        $order->setStatus(Order::STATUS_DRAFT);
        $order->setNumber();
        $order->setDueDate();
        $order->setBalance($quote->total);

        $order->quote_id = $quote->id;
        $order->task_id = $quote->task_id;
        $order->discount_total = $quote->discount_total;
        $order->tax_total = $quote->tax_total;
        $order->is_amount_discount = $quote->is_amount_discount ?: false;
        $order->footer = $quote->footer;
        $order->public_notes = $quote->public_notes;
        $order->private_notes = $quote->private_notes;
        $order->tax_rate = $quote->tax_rate;
        $order->terms = $quote->terms;
        $order->sub_total = $quote->sub_total;
        $order->partial = $quote->partial;
        $order->partial_due_date = $quote->partial_due_date;
        $order->date = $quote->date;
        $order->line_items = $quote->line_items;
        return $order;
    }
}
