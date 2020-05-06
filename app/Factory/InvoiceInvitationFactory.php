<?php

namespace App\Factory;

use App\InvoiceInvitation;
use Illuminate\Support\Str;

class InvoiceInvitationFactory
{
    public static function create(int $account_id, int $user_id): InvoiceInvitation
    {
        $ii = new InvoiceInvitation;
        $ii->account_id = $account_id;
        $ii->user_id = $user_id;
        $ii->key = Str::random(20);

        return $ii;
    }

}
