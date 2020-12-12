<?php

namespace App\Services\Mail;

use App\Models\Cases;
use BeyondCode\Mailbox\InboundEmail;

class CaseMailHandler {
    public function __invoke(InboundEmail $email) {
        $from = $email->from();
        $contact = CustomerContact::whereEmail($from)->first();
        
        Cases::create([
            'customer_id'  => $contact->customer->id,
            'contact_id'   => $contact->id,
            'subject'      => $email->subject(),
            'message'      => $email->text(),
        ]);
    }
}
