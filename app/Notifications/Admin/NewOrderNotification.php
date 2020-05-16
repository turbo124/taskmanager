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
        $message_array = $this->buildMessage();

        return (new MailMessage)->subject($this->buildSubject())->markdown(
            'email.admin.new',
            [
                'data' => $message_array
            ]
        );
    }

    private function buildSubject()
    {
         return trans('texts.notification_order_subject', $this->getDataArray());
    }

    private function buildMessage()
    {
        return [
                    'title'       => $subject,
                    'message'     => trans('texts.notification_order', $this->getDataArray()),
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
        $logo = $this->order->account->present()->logo();

        return (new SlackMessage)->success()
                                 ->from("System")->image($logo)->content(
               $this->buildSubject()
            );
    }

}

