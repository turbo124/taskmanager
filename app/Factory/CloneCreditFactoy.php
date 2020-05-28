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
        $clone_credit->status_id = credit::STATUS_DRAFT;
        $clone_credit->number = null;
        $clone_credit->partial_due_date = null;
        $clone_credit->user_id = $user->id;
        $clone_credit->balance = $credit->total;
        $clone_credit->account_id = $credit->account_id;
        $clone_credit->due_date = !empty($credit->customer->getSetting('payment_terms')) ? Carbon::now()->addDays(
            $credit->customer->getSetting('payment_terms')
        )->format('Y-m-d H:i:s') : $credit->due_date;

        return $clone_credit;
    }
}
