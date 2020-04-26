<?php

namespace App\Factory;

use App\Lead;
use App\Account;
use App\User;

class LeadFactory
{
    public static function create(Account $account, User $user): Lead
    {
        $lead = new Lead;
        $lead->account_id = $account->id;
        $lead->user_id = $user->id;
        $lead->source_type = 1;

        return $lead;
    }
}
