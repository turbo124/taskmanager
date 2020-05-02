<?php

namespace App\Services\Quote;

use App\Jobs\Email\SendEmail;
use App\Quote;
use App\Traits\MakesInvoiceHtml;
use App\Events\Quote\QuoteWasEmailed;
use Carbon\Carbon;

class QuoteEmail
{
    use MakesInvoiceHtml;

    private $quote;

    /**
     * @var string|null
     */
    private $template = '';
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
     * @param null $template
     * @param null $contact
     */
    public function __construct($quote, $subject = '', $body = '', $template = null, $contact = null)
    {
        $this->quote = $quote;
        $this->template = $template;
        $this->contact = $contact;
        $this->subject = $subject;
        $this->body = $body;
    }


    public function run()
    {
         if($this->quote->invitations->count() === 0) {
            return true;
        }

       foreach($this->quote->invitations as $invitation) {
            $footer = ['link' => $invitation->getLink(), 'text' => trans('texts.view_invoice')];

            if ($invitation->contact->send_email && $invitation->contact->email) {
                SendEmail::dispatchNow($this->quote, $subject, $body, $this->template, $invitation->contact, $footer);
            }
        }

        event(new QuoteWasEmailed($this->quote->invitations->first()));
    }
}
