<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\PurchaseOrderInvitation;
use App\Models\User;
use Illuminate\Support\Str;

class PurchaseOrderInvitationFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return PurchaseOrderInvitation
     */
    public static function create(Account $account, User $user): PurchaseOrderInvitation
    {
        $ii = new PurchaseOrderInvitation();
        $ii->account_id = $account->id;
        $ii->user_id = $user->id;
        $ii->key = Str::random(20);

        return $ii;
    }

}
