<?php

namespace App\Factory;

use App\Customer;
use App\Quote;
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
    public static function create(int $account_id,
        int $user_id,
        Customer $customer): Quote
    {
        $quote = new Quote();
        $quote->account_id = $account_id;
        $quote->status_id = Quote::STATUS_DRAFT;
        $quote->user_id = $user_id;
        $quote->customer_id = $customer->id;

        return $quote;
    }
}
