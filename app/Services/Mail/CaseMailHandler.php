<?php

namespace App\Services\Mail;

use App\Models\Cases;
use BeyondCode\Mailbox\InboundEmail;

class CaseMailHandler {
    public function __invoke(InboundEmail $email) {
        Cases::create([
            'sender'    => $email->from(),
            'subject'   => $email->subject(),
            'body'      => $email->text(),
        ]);
    }
}
