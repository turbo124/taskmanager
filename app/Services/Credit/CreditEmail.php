<?php

namespace App\Services\Credit;

use App\Jobs\Email\SendEmail;
use App\Credit;
use App\Traits\MakesInvoiceHtml;
use Carbon\Carbon;

class CreditEmail
{
    use MakesInvoiceHtml;

    private $credit;
    private $reminder_template;
    private $contact;

    /**
     * @var string
     */
    private $subject = '';

    /**
     * @var string
     */
    private $body = '';

    /**
     * CreditEmail constructor.
     * @param Credit $credit
     * @param string $subject
     * @param string $body
     * @param null $reminder_template
     * @param null $contact
     */
    public function __construct(Credit $credit, $subject = '', $body = '', $reminder_template = null, $contact = null)
    {
        $this->credit = $credit;
        $this->reminder_template = $reminder_template;
        $this->contact = $contact;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Builds the correct template to send
     * @param string $reminder_template The template name ie reminder1
     * @return array
     */
    public function run()
    {
        $subject = strlen($this->subject) > 0 ? $this->subject : $this->credit->customer->getSetting('email_subject_' . $this->reminder_template);
        $body = strlen($this->body) > 0 ? $this->body : $this->credit->customer->getSetting('email_template_' . $this->reminder_template);


        $this->credit->invitations->each(function ($invitation) use ($subject, $body) {

            $footer = ['link' => $invitation->getLink(), 'text' => trans('texts.view_invoice')];

            if ($invitation->contact->send_email && $invitation->contact->email) {
                SendEmail::dispatchNow($this->credit, $subject, $body, $this->reminder_template, $invitation->contact, $footer);
            }
        });
    }
}
