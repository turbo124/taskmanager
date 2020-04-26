<?php

namespace App\Factory;

use App\RecurringQuote;
use App\Account;
use App\User;
use App\Customer;

class RecurringQuoteFactory
{
    public static function create(Customer $customer, Account $account, int $total, User $user): RecurringQuote
    {
        $quote = new RecurringQuote();
        $quote->account_id = $account->id;
        $quote->status_id = RecurringQuote::STATUS_DRAFT;
        $quote->total = $total;
        $quote->balance = $total;
        $quote->user_id = $user->id;
        $quote->customer_id = $customer->id;
        $quote->frequency_id = RecurringQuote::FREQUENCY_MONTHLY;
     
        return $quote;
    }
}
