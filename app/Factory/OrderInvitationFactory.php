<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\OrderInvitation;
use App\Models\User;
use Illuminate\Support\Str;

class OrderInvitationFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return OrderInvitation
     */
    public static function create(Account $account, User $user): OrderInvitation
    {
        $qi = new OrderInvitation;
        $qi->account_id = $account->id;
        $qi->user_id = $user->id;
        $qi->key = Str::random(20);

        return $qi;
    }

}
