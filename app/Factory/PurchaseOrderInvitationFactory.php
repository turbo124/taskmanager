<?php

namespace App\Factory;

use App\Models\PurchaseOrderInvitation;
use Illuminate\Support\Str;

class PurchaseOrderInvitationFactory
{
    public static function create(int $account_id, int $user_id): PurchaseOrderInvitation
    {
        $ii = new PurchaseOrderInvitation();
        $ii->account_id = $account_id;
        $ii->user_id = $user_id;
        $ii->key = Str::random(20);

        return $ii;
    }

}
