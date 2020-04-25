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
        $ii->client_contact_id = null;
        $ii->invoice_id = null;
        $ii->key = Str::random(20);
        $ii->transaction_reference = null;
        $ii->message_id = null;
        $ii->email_error = '';
        $ii->signature_base64 = '';
        $ii->signature_date = null;
        $ii->sent_date = null;
        $ii->viewed_date = null;
        $ii->opened_date = null;
        return $ii;
    }

}
