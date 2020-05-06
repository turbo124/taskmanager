<?php

namespace App\Factory;

use App\Lead;
use App\Account;
use App\User;

class LeadFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return Lead
     */
    public static function create(Account $account, User $user): Lead
    {
        $lead = new Lead;
        $lead->account_id = $account->id;
        $lead->user_id = $user->id;
        $lead->source_type = 1;
        $lead->status_id = Lead::NEW_LEAD;

        return $lead;
    }
}
