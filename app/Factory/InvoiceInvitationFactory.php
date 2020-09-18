<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\InvoiceInvitation;
use App\Models\User;
use Illuminate\Support\Str;

class InvoiceInvitationFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return InvoiceInvitation
     */
    public static function create(Account $account, User $user): InvoiceInvitation
    {
        $ii = new InvoiceInvitation;
        $ii->account_id = $account->id;
        $ii->user_id = $user->id;
        $ii->key = Str::random(20);

        return $ii;
    }

}
