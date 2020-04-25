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

    protected $order;
    protected $account;

    public function __construct(Order $order, $account)
    {
        $this->order = $order;
    }


     /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return isset($this->entity->account->settings->slack_enabled) && $this->entity->account->settings->slack_enabled === true ? ['mail', 'slack'] : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $total = Number::formatMoney($this->order->total, $this->order->customer);
        $subject = trans('texts.notification_order_subject', [
            'customer' => $this->order->customer->present()->name(),
            'order'  => $this->order->number,
        ]);


        return (new MailMessage)->subject($subject)->markdown('email.admin.new', ['data' => [
            'title'     => $subject,
            'message'   => trans('texts.notification_order', [
                'total'  => $total,
                'customer'  => $this->order->customer->present()->name(),
                'invoice' => $this->order->number,
            ]),
            'url'       => config('taskmanager.site_url') . '/invoices/' . $this->order->id,
            'button_text'    => trans('texts.view_invoice'),
            'signature' => isset($this->order->account->settings->email_signature) ? $this->order->account->settings->email_signature : '',
            'logo'      => $this->order->account->present()->logo(),
        ]]);
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
            ->from("System")->image($logo)->content(trans('texts.notification_deal',
                ['total' => Number::formatMoney($this->deal->valued_at, $this->deal->customer), 'customer' => $this->deal->customer->present()->name()]));
    }

}

