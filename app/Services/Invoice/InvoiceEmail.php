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
    private $template = '';
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
     * @param null $template
     * @param null $contact
     */
    public function __construct($invoice, $subject = '', $body = '', $template = null, $contact = null)
    {
        $this->invoice = $invoice;
        $this->template = $template;
        $this->contact = $contact;
        $this->subject = $subject;
        $this->body = $body;
    }


    public function run()
    {
        if($this->invoice->invitations->count() === 0) {
            return true;
        }

        foreach($this->invoice->invitations as $invitation) {
            
            $footer = ['link' => $invitation->getLink(), 'text' => trans('texts.view_invoice')];

            if ($invitation->contact->send_email && $invitation->contact->email) {
                SendEmail::dispatchNow($this->invoice, $this->subject, $this->body, $this->template, $invitation->contact, $footer);
            }
        }

        event(new InvoiceWasEmailed($this->invoice->invitations->first()));
    }
}
