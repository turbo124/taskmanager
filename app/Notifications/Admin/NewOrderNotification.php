<?php

namespace App\Notifications\Admin;

use App\Order;
use App\Utils\Number;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private Order $order;

    private string $message_type;

    public function __construct(Order $order, string $message_type = '')
    {
        $this->order = $order;
        $this->message_type = $message_type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return !empty($this->message_type)
            ? [$this->message_type]
            : [
                $notifiable->account_user()->default_notification_type
            ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $this->build();

        return (new MailMessage)->subject($this->subject)->markdown(
            'email.admin.new',
            [
                'data' => $this->message_array
            ]
        );
    }

    private function build()
    {
        $this->setSubject();
        $this->setMessage();
        $this->buildMessage();
    }

    private function setSubject()
    {
         $this->subject = trans('texts.notification_order_subject', $this->getDataArray());
    }

    private function setMessage()
    {
        $this->message = trans('texts.notification_order', $this->getDataArray());
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

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [//
        ];
    }

    public function toSlack($notifiable)
    {
        $this->build();
        $logo = $this->order->account->present()->logo();

        return (new SlackMessage)->success()
                                 ->from("System")->image($logo)->content(
               $this->subject
            );
    }

}

