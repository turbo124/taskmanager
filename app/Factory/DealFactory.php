<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\Deal;
use App\Models\User;

class DealFactory
{
    public static function create(User $user, Account $account): Task
    {
        $deal = new Deal;
        $deal->source_type = 1;
        $deal->user_id = $user->id;
        $deal->account_id = $account->id;

        return $task;
    }
}
