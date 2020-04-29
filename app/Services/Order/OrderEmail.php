<?php

namespace App\Services\Order;

use App\Jobs\Email\SendEmail;
use App\Order;
use App\Traits\MakesInvoiceHtml;
use Carbon\Carbon;

class OrderEmail
{
    use MakesInvoiceHtml;

    private $order;
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
     * OrderEmail constructor.
     * @param Order $order
     * @param string $subject
     * @param string $body
     * @param null $template
     * @param null $contact
     */
    public function __construct(Order $order, $subject = '', $body = '', $template = null, $contact = null)
    {
        $this->order = $order;
        $this->template = $template;
        $this->contact = $contact;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function run()
    {
         if($this->order->invitations->count() === 0) {
            return true;
        }

        $subject = strlen($this->subject) > 0 ? $this->subject : $this->order->customer->getSetting('email_subject_' . $this->template);
        $body = strlen($this->body) > 0 ? $this->body : $this->order->customer->getSetting('email_template_' . $this->template);

        foreach($this->order->invitations as $invitation) {
            $footer = ['link' => $invitation->getLink(), 'text' => trans('texts.view_invoice')];
           
            if ($invitation->contact->send_email && $invitation->contact->email) {
                SendEmail::dispatchNow($this->order, $subject, $body, $this->template, $invitation->contact, $footer);
            }
        }
    }
}
