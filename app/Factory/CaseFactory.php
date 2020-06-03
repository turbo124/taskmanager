<?php

namespace App\Factory;

use App\Cases;
use App\Credit;
use App\Account;
use App\User;
use App\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CaseFactory
{
    public static function create(
        Account $account,
        User $user,
        Customer $customer
    ): Cases {
        $case = new Cases;
        $case->setStatus(Cases::STATUS_DRAFT);
        $case->setCustomer($customer);
        $case->setAccount($account);
        $case->setUser($user);
        return $case;
    }
}
