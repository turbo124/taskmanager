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
        
        foreach($this->order->invitations as $invitation) {
            $footer = ['link' => $invitation->getLink(), 'text' => trans('texts.view_invoice')];
           
            if ($invitation->contact->send_email && $invitation->contact->email) {
                SendEmail::dispatchNow($this->order, $this->subject, $this->body, $this->template, $invitation->contact, $footer);
            }
        }
    }
}
