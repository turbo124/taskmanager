<?php

namespace App\Factory;

use App\Company;
use App\Account;
use App\User;

class CompanyFactory
{
    /**
     * @param int $user_id
     * @return Company
     */
    public function create(User $user, Account $account): Company
    {
        $company = new Company;
        $company->user_id = $user->id;
        $company->account_id = $account->id;
        $company->country_id = 225;
        $company->currency_id = 2;

        return $company;
    }
}
