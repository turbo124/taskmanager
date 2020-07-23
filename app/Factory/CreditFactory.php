<?php

namespace App\Factory;

use App\Models\Credit;
use App\Models\Account;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CreditFactory
{
    public static function create(
        Account $account,
        User $user,
        Customer $customer
    ): Credit {
        $credit = new Credit;
        $credit->setStatus(Credit::STATUS_DRAFT);
        $credit->setCustomer($customer);
        $credit->setAccount($account);
        $credit->setUser($user);
        $credit->tax_rate = 0;
        return $credit;
    }
}
