<?php

namespace App\Factory;

use App\Credit;
use App\Account;
use App\User;
use App\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CreditFactory
{
    public static function create(Account $account,
        User $user,
        Customer $customer): Credit
    {
        $credit = new Credit;
        $credit->status_id = Credit::STATUS_DRAFT;
        $credit->customer_id = $customer->id;
        $credit->account_id = $account->id;
        $credit->user_id = $user->id;
        $credit->tax_rate = 0;
        return $credit;
    }
}
