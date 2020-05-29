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
