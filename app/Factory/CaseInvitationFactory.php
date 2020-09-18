<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\CaseInvitation;
use App\Models\User;
use Illuminate\Support\Str;

class CaseInvitationFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return CaseInvitation
     */
    public static function create(Account $account, User $user): CaseInvitation
    {
        $ii = new CaseInvitation();
        $ii->account_id = $account->id;
        $ii->user_id = $user->id;
        $ii->key = Str::random(20);

        return $ii;
    }

}
