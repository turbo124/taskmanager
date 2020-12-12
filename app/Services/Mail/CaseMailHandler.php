<?php

namespace App\Services\Mail;

use App\Models\Cases;
use BeyondCode\Mailbox\InboundEmail;

class CaseMailHandler {
    public function __invoke(InboundEmail $email, $token)
    {
        $from = $email->from();
        $contact = CustomerContact::whereEmail($from)->first();
        

        $case = Cases::where('token', $token)->first();

        if(!$case) {
            Cases::create([
                'customer_id'  => $contact->customer->id,
                'contact_id'   => $contact->id,
                'subject'      => $email->subject(),
                'message'      => $email->text(),
            ]);
        }
 
        // handle attachments
         $this->storeAttachments($case, $email);
    }
}
