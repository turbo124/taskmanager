<?php

namespace App\Factory;

use App\Customer;
use App\Order;

/**
 * Class OrderFactory
 * @package App\Factory
 */
class OrderFactory
{
    /**
     * @param int $customer_id
     * @param $user_id
     * @param $account_id
     * @param int $total
     * @param object|null $settings
     * @param Customer|null $customer
     * @return Invoice
     */
    public static function create(int $account_id,
        int $user_id,
        Customer $customer): Order
    {
        $order = new Order;
        $order->account_id = $account_id;
        $order->balance = 0;
        $order->status_id = Order::STATUS_DRAFT;
        $order->discount_total = 0;
        $order->tax_total = 0;
        $order->footer = '';
        $order->terms = '';
        $order->public_notes = '';
        $order->private_notes = '';
        $order->tax_rate_name = '';
        $order->tax_rate = 0;
        $order->date = null;
        $order->partial_due_date = null;
        $order->total = 0;
        $order->user_id = $user_id;
        $order->partial = 0;
        $order->customer_id = $customer->id;
        $order->custom_value1 = '';
        $order->custom_value2 = '';
        $order->custom_value3 = '';
        $order->custom_value4 = '';

        return $order;
    }
}
