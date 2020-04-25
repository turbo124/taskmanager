<?php

namespace App\Services\Invoice;

use App\Events\Invoice\InvoiceWasEmailed;
use App\Invoice;
use App\Jobs\Email\SendEmail;
use App\Traits\MakesInvoiceHtml;
use Illuminate\Support\Carbon;

class InvoiceEmail
{
    use MakesInvoiceHtml;

    private $invoice;

    /**
     * @var string|null
     */
    private $reminder_template = '';
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
     * InvoiceEmail constructor.
     * @param $invoice
     * @param string $subject
     * @param string $body
     * @param null $reminder_template
     * @param null $contact
     */
    public function __construct($invoice, $subject = '', $body = '', $reminder_template = null, $contact = null)
    {
        $this->invoice = $invoice;
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
        $subject = strlen($this->subject) > 0 ? $this->subject : $this->invoice->customer->getSetting('email_subject_' . $this->reminder_template);
        $body = strlen($this->body) > 0 ? $this->body : $this->invoice->customer->getSetting('email_template_' . $this->reminder_template);

        $this->invoice->invitations->each(function ($invitation) use ($subject, $body) {

            $footer = ['link' => $invitation->getLink(), 'text' => trans('texts.view_invoice')];

            if ($invitation->contact->send_email && $invitation->contact->email) {
                SendEmail::dispatchNow($this->invoice, $subject, $body, $this->reminder_template, $invitation->contact, $footer);
            }
        });

        if ($this->invoice->invitations->count() > 0) {
            event(new InvoiceWasEmailed($this->invoice->invitations->first()));
        }
    }
}
