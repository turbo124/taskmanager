<?php

namespace App\Factory;

use App\RecurringQuote;

class RecurringQuoteFactory
{
    public static function create(int $customer_id, int $account_id, int $total): RecurringQuote
    {
        $quote = new RecurringQuote();
        $quote->account_id = $account_id;
        $quote->status_id = RecurringQuote::STATUS_DRAFT;
        $quote->total = $total;
        $quote->balance = $total;
        $quote->user_id = auth()->user()->id;
        $quote->customer_id = $customer_id;
        $quote->frequency_id = RecurringQuote::FREQUENCY_MONTHLY;
     
        return $quote;
    }
}
