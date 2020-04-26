<?php

namespace App\Factory;

use App\Account;
use App\User;
use App\Customer;
use App\Settings;

class CustomerFactory
{
    public static function create(Account $account, User $user, Company $company = null): Customer
    {
        $client = new Customer;
        $client->currency_id = 2;
        $client->account_id = $account->id;
        $client->company_id = $company->id;
        $client->user_id = $user->id;
        $client->settings = (new Settings)->getAccountDefaults();

        return $client;
    }
}
