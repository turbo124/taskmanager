<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\CreditInvitation;
use App\Models\User;
use Illuminate\Support\Str;

class CreditInvitationFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return CreditInvitation
     */
    public static function create(Account $account, User $user): CreditInvitation
    {
        $ci = new CreditInvitation;
        $ci->account_id = $account->id;
        $ci->user_id = $user->id;
        $ci->key = Str::random(40);

        return $ci;
    }
}
