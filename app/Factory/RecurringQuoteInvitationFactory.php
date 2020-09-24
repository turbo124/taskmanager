<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\RecurringQuoteInvitation;
use App\Models\User;
use Illuminate\Support\Str;

class RecurringQuoteInvitationFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return RecurringQuoteInvitation
     */
    public static function create(Account $account, User $user): RecurringQuoteInvitation
    {
        $ii = new RecurringQuoteInvitation();
        $ii->account_id = $account->id;
        $ii->user_id = $user->id;
        $ii->key = Str::random(20);

        return $ii;
    }

}
