<?php

namespace App\Factory;

use App\Credit;
use App\User;
use App\Account;
use Carbon\Carbon;

class CloneCreditFactory
{
    /**
     * @param $credit
     * @param User $user
     * @return Credit
     */
    public static function create($credit, User $user): Credit
    {
        $clone_credit = new Credit();
        $clone_credit->fill($credit->toArray());
        $clone_credit->setStatus(Credit::STATUS_DRAFT);
        $clone_credit->setNumber();
        $clone_credit->setUser($user);
        $clone_credit->setBalance($credit->total);
        $clone_credit->setAccount($credit->account);
        $clone_credit->setDueDate();

        return $clone_credit;
    }
}
