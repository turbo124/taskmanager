<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\QuoteInvitation;
use App\Models\User;
use Illuminate\Support\Str;

class QuoteInvitationFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return QuoteInvitation
     */
    public static function create(Account $account, User $user): QuoteInvitation
    {
        $qi = new QuoteInvitation;
        $qi->account_id = $account->id;
        $qi->user_id = $user->id;
        $qi->key = Str::random(20);

        return $qi;
    }

}
