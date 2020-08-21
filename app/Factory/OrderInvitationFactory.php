<?php

namespace App\Factory;

use App\Models\OrderInvitation;
use Illuminate\Support\Str;

class OrderInvitationFactory
{
    public static function create(int $account_id, int $user_id): OrderInvitation
    {
        $qi = new OrderInvitation;
        $qi->account_id = $account_id;
        $qi->user_id = $user_id;
        $qi->key = Str::random(20);

        return $qi;
    }

}
