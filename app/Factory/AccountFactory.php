<?php

namespace App\Factory;


use App\Account;
use App\Settings\AccountSettings;
use Illuminate\Support\Str;

class AccountFactory
{

    /**
     * @param int $account_id
     * @return Account
     */
    public static function create(int $account_id): Account
    {
        $account = new Account;
        // $company->name = '';
        $account->domain_id = $account_id;
        $account->subdomain = '';
        $account->settings = (new AccountSettings)->getAccountDefaults();

        return $account;
    }
}