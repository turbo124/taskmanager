<?php

namespace App\Factory;

use App\Models\Cases;
use App\Models\Credit;
use App\Models\Account;
use App\Models\User;
use App\Models\Customer;
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
