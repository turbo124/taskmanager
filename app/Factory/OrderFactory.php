<?php

namespace App\Factory;

use App\Account;
use App\User;
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
    public static function create(
        Account $account,
        User $user,
        Customer $customer
    ): Order {
        $order = new Order;
        $order->account_id = $account->id;
        $order->status_id = Order::STATUS_DRAFT;
        $order->customer_id = $customer->id;
        $order->user_id = $user->id;

        return $order;
    }
}
