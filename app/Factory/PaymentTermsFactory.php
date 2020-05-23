<?php

namespace App\Factory;

use App\GroupSetting;
use App\Account;
use App\User;

class PaymentTermsFactory
{
    public static function create(Account $account, User $user): GroupSetting
    {
        $gs = new PaymentTerms;
        $gs->name = '';
        $gs->account_id = $account->id;
        $gs->user_id = $user->id;

        return $gs;
    }
}
