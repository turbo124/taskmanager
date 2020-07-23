<?php

namespace App\Mail\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderBackorderedMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\Order
     */
    private Order $order;

    /**
     * @var User
     */
    private User $user;

    private $message;

    /**
     * @var array
     */
    private array $message_array;

    /**
     * OrderCreated constructor.
     * @param \App\Models\Order $order
     * @param User $user
     */
    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->setSubject();
        $this->setMessage();
        $this->buildMessage();

        return $this->to($this->user->email)
                    ->from('tamtamcrm@support.com')
                    ->subject($this->subject)
                    ->markdown(
                        'email.admin.new',
                        [
                            'data' => $this->message_array
                        ]
                    );
    }

    private function setSubject()
    {
        $this->subject = trans('texts.notification_order_backordered_subject', $this->getDataArray());
    }

    private function setMessage()
    {
        $this->message = trans('texts.notification_order_backordered', $this->getDataArray());
    }

    private function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'message'     => $this->message,
            'url'         => config('taskmanager.site_url') . '/invoices/' . $this->order->id,
            'button_text' => trans('texts.view_invoice'),
            'signature'   => isset($this->order->account->settings->email_signature) ? $this->order->account->settings->email_signature : '',
            'logo'        => $this->order->account->present()->logo(),
        ];
    }

    private function getDataArray()
    {
        return [
            'total'    => $this->order->getFormattedTotal(),
            'customer' => $this->order->customer->present()->name(),
            'order'    => $this->order->getNumber(),
        ];
    }
}
