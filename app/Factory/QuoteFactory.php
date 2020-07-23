<?php

namespace App\Factory;

use App\Models\Quote;
use App\Models\Account;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class QuoteFactory
{
    /**
     * @param int $customer_id
     * @param int $account_id
     * @param int $user_id
     * @param $total
     * @return \App\Models\Quote
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
