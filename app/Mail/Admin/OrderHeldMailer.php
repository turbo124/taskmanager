<?php

namespace App\Mail\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class OrderHeldMailer extends AdminMailer
{
    use Queueable, SerializesModels;

    /**
     * @var Order
     */
    private Order $order;

    /**
     * OrderCreated constructor.
     * @param Order $order
     * @param User $user
     */
    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->entity = $order;
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
        $this->execute();
    }

    private function setSubject()
    {
        $this->subject = trans('texts.notification_order_held_subject', $this->getDataArray());
    }

    private function getDataArray()
    {
        return [
            'total'    => $this->order->getFormattedTotal(),
            'customer' => $this->order->customer->present()->name(),
            'order'    => $this->order->getNumber(),
        ];
    }

    private function setMessage()
    {
        $this->message = trans('texts.notification_order_held', $this->getDataArray());
    }

    private function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'body'        => $this->message,
            'url'         => config('taskmanager.site_url') . '/portal/view/order/' . $this->order->id,
            'button_text' => trans('texts.view_invoice'),
            'signature'   => isset($this->order->account->settings->email_signature) ? $this->order->account->settings->email_signature : '',
            'logo'        => $this->order->account->present()->logo(),
        ];
    }
}
