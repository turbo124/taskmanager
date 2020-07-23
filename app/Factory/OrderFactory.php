<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;

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
        $order->setAccount($account);
        $order->setStatus(Order::STATUS_DRAFT);
        $order->setCustomer($customer);
        $order->setUser($user);

        return $order;
    }
}
