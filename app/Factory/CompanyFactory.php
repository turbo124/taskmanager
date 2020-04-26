<?php

namespace App\Factory;

use App\Company;

class CompanyFactory
{
    /**
     * @param int $user_id
     * @return Company
     */
    public function create(int $user_id, int $account_id): Company
    {
        $company = new Company;
        $company->user_id = $user_id;
        $company->account_id = $account_id;
        $company->country_id = 225;
        $company->currency_id = 2;

        return $company;
    }
}
