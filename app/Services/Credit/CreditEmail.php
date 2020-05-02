<?php

namespace App\Services\Credit;

use App\Jobs\Email\SendEmail;
use App\Credit;
use App\Traits\MakesInvoiceHtml;
use Carbon\Carbon;
use App\Events\Credit\CreditWasEmailed;

class CreditEmail
{
    use MakesInvoiceHtml;

    private $credit;
    private $template;
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
     * @param null $template
     * @param null $contact
     */
    public function __construct(Credit $credit, $subject = '', $body = '', $template = null, $contact = null)
    {
        $this->credit = $credit;
        $this->template = $template;
        $this->contact = $contact;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function run()
    {
        if($this->credit->invitations->count() === 0) {
            return true;
        }

        foreach($this->credit->invitations as $invitation) {

            $footer = ['link' => $invitation->getLink(), 'text' => trans('texts.view_invoice')];

            if ($invitation->contact->send_email && $invitation->contact->email) {
                SendEmail::dispatchNow($this->credit, $this->subject, $this->body, $this->template, $invitation->contact, $footer);
            }
        }

        event(new CreditWasEmailed($this->credit));
    }
}
