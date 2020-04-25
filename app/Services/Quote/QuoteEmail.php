<?php

namespace App\Services\Quote;

use App\Jobs\Email\SendEmail;
use App\Quote;
use App\Traits\MakesInvoiceHtml;
use Carbon\Carbon;

class QuoteEmail
{
    use MakesInvoiceHtml;

    private $quote;

    /**
     * @var string|null
     */
    private $reminder_template = '';
    private $contact;

    /**
     * @var
     */
    private $subject;

    /**
     * @var
     */
    private $body;

    /**
     * QuoteEmail constructor.
     * @param $quote
     * @param string $subject
     * @param string $body
     * @param null $reminder_template
     * @param null $contact
     */
    public function __construct($quote, $subject = '', $body = '', $reminder_template = null, $contact = null)
    {
        $this->quote = $quote;
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
        $subject = strlen($this->subject) > 0 ? $this->subject : $this->quote->customer->getSetting('email_subject_' . $this->reminder_template);
        $body = strlen($this->body) > 0 ? $this->body : $body_template = $this->quote->customer->getSetting('email_template_' . $this->reminder_template);

        $this->quote->invitations->each(function ($invitation) use ($subject, $body) {
            $footer = ['link' => $invitation->getLink(), 'text' => trans('texts.view_invoice')];
            
            if ($invitation->contact->send_email && $invitation->contact->email) {
                SendEmail::dispatchNow($this->quote, $subject, $body, $this->reminder_template, $invitation->contact, $footer);
            }
        });
    }
}
