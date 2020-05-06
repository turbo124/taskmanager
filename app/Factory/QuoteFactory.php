<?php

namespace App\Factory;

use App\Quote;
use App\Account;
use App\User;
use App\Customer;
use Illuminate\Support\Facades\Log;

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
        $quote->account_id = $account->id;
        $quote->status_id = Quote::STATUS_DRAFT;
        $quote->user_id = $user->id;
        $quote->customer_id = $customer->id;

        return $quote;
    }
}
