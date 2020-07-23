<?php

namespace App\Factory;

use App\Models\QuoteInvitation;
use Illuminate\Support\Str;

class QuoteInvitationFactory
{
    public static function create(int $account_id, int $user_id): QuoteInvitation
    {
        $qi = new QuoteInvitation;
        $qi->account_id = $account_id;
        $qi->user_id = $user_id;
        $qi->key = Str::random(20);

        return $qi;
    }

}
