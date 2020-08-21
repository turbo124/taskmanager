<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Quote;
use App\Models\User;

class QuoteFactory
{
    /**
     * @param int $customer_id
     * @param int $account_id
     * @param int $user_id
     * @param $total
     * @return Quote
     */
    public static function create(
        Account $account,
        User $user,
        Customer $customer
    ): Quote {
        $quote = new Quote();
        $quote->setAccount($account);
        $quote->setStatus(Quote::STATUS_DRAFT);
        $quote->setUser($user);
        $quote->setCustomer($customer);

        return $quote;
    }
}
