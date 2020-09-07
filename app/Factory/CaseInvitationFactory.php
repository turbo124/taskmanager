<?php

namespace App\Factory;

use App\Models\CaseInvitation;
use Illuminate\Support\Str;

class CaseInvitationFactory
{
    public static function create(int $account_id, int $user_id): CaseInvitation
    {
        $ii = new CaseInvitation();
        $ii->account_id = $account_id;
        $ii->user_id = $user_id;
        $ii->key = Str::random(20);

        return $ii;
    }

}
