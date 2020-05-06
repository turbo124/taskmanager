<?php

namespace App\Factory;

use App\Credit;
use App\User;
use App\Account;

class CloneCreditFactory
{
    public static function create(Credit $credit, User $user): Credit
    {
        $clone_credit = $credit->replicate();
        $clone_credit->status_id = credit::STATUS_DRAFT;
        $clone_credit->number = null;
        $clone_credit->partial_due_date = null;
        $clone_credit->user_id = $user->id;
        $clone_credit->balance = $credit->total;

        return $clone_credit;
    }
}
