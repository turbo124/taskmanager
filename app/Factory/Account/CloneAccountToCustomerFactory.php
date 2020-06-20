<?php

namespace App\Factory\Account;

use App\ClientContact;
use App\Customer;
use App\User;
use App\Account;

class CloneAccountToCustomerFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return Customer
     */
    public static function create(Account $account, User $user): Customer
    {
        $customer = new Customer();
        $customer->account_id = $account->id;
        $customer->user_id = $user->id;
        $customer->name = $account->settings->name;
        $customer->phone = $account->settings->phone;
        $customer->website = $account->settings->website;
        $customer->currency_id = $account->settings->currency_id;

        return $customer;
    }
}
