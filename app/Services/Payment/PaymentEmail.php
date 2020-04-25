<?php

namespace App\Services\Payment;

use App\Jobs\Email\SendEmail;
use App\Payment;

class PaymentEmail
{

    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Builds the correct template to send
     * @param string $reminder_template The template name ie reminder1
     * @return array
     */
    public function run()
    {
        $subject = $this->payment->customer->getSetting('email_subject_payment');
        $body = $this->payment->customer->getSetting('email_template_payment');

        $this->payment->customer->contacts->each(function ($contact) use ($subject, $body) {
            if ($contact->send_email && $contact->email) {
               SendEmail::dispatchNow($this->payment, $subject, $body, 'payment', $contact);
            }
        });
    }
}
