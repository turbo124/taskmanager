<?php

namespace App\Factory;

use App\Models\CreditInvitation;
use Illuminate\Support\Str;

class CreditInvitationFactory
{
    public static function create(int $account_id, int $user_id): CreditInvitation
    {
        $ci = new CreditInvitation;
        $ci->account_id = $account_id;
        $ci->user_id = $user_id;
        $ci->key = Str::random(40);

        return $ci;
    }
}
