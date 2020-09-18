<?php

namespace App\Factory;

use App\Models\Account;
use App\Models\PurchaseOrderInvitation;
use App\Models\RecurringInvoiceInvitation;
use App\Models\User;
use Illuminate\Support\Str;

class RecurringInvoiceInvitationFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return RecurringInvoiceInvitation
     */
    public static function create(Account $account, User $user): RecurringInvoiceInvitation
    {
        $ii = new RecurringInvoiceInvitation();
        $ii->account_id = $account->id;
        $ii->user_id = $user->id;
        $ii->key = Str::random(20);

        return $ii;
    }

}
