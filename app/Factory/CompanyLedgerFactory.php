<?php

namespace App\Factory;

use App\CompanyLedger;

class CompanyLedgerFactory
{
    public static function create(int $account_id, int $user_id): CompanyLedger
    {
        $company_ledger = new CompanyLedger;
        $company_ledger->account_id = $account_id;
        $company_ledger->user_id = $user_id;

        return $company_ledger;
    }
}
