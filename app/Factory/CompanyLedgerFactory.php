<?php

namespace App\Factory;

use App\CompanyLedger;
use App\Account;
use App\User;

class CompanyLedgerFactory
{
    public static function create(Account $account, User $user): CompanyLedger
    {
        $company_ledger = new CompanyLedger;
        $company_ledger->account_id = $account->id;
        $company_ledger->user_id = $user->id;

        return $company_ledger;
    }
}
