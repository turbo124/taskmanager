<?php

namespace App\Factory;

use App\Credit;
use App\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CreditFactory
{
    public static function create(int $account_id,
        int $user_id,
        Customer $customer): Credit
    {
        $credit = new Credit;
        $credit->status_id = Credit::STATUS_DRAFT;
        $credit->customer_id = $customer->id;
        $credit->account_id = $account_id;
        $credit->user_id = $user_id;
        $credit->tax_rate = 0;
        return $credit;
    }
}
