<?php

namespace App\Factory\Account;

use App\Models\ClientContact;
use App\Models\Customer;
use App\Models\User;
use App\Models\Account;

class CloneAccountToCustomerFactory
{
    /**
     * @param Account $account
     * @param \App\Models\User $user
     * @return \App\Models\Customer
     */
    public static function create(Account $account, User $user): Customer
    {
        $customer = new Customer();
        $customer->account_id = $account->id;
        $customer->user_id = $user->id;
        $customer->name = $account->settings->name;
        $customer->phone = !empty($account->settings->phone) ? $account->settings->phone : '';
        $customer->website = !empty($account->settings->website) ? $account->settings->website : '';
        $customer->currency_id = $account->settings->currency_id;

        return $customer;
    }
}
