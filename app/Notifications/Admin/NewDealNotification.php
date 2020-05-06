<?php

namespace App\Notifications\Admin;

use App\Utils\Number;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class NewDealNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $deal;
    private string $message_type;

    public function __construct($deal, string $message_type = '')
    {
        $this->deal = $deal;
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
        return !empty($this->message_type) ? [$this->message_type] : [$notifiable->account_user()->default_notification_type];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $total = Number::formatCurrency($this->deal->valued_at, $this->deal->customer);

        return (new MailMessage)->subject(trans('texts.notification_deal_subject',
            ['customer' => $this->deal->customer->present()->name(),]))->markdown('email.admin.new', ['data' => [
            'title'       => trans('texts.notification_deal_subject', ['customer' => $this->deal->customer->present()->name()]),
            'message'     => trans('texts.notification_deal', [
                'total'    => $total,
                'customer' => $this->deal->customer->present()->name()
            ]),
            'url'         => config('taskmanager.site_url') . 'portal/payments/' . $this->deal->id,
            'button_text' => trans('texts.view_deal'),
            'signature'   => !empty($this->settings) ? $this->settings->email_signature : '',
            'logo'        => $this->deal->account->present()->logo(),
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
        $logo = $this->deal->account->present()->logo();
        $total = Number::formatCurrency($this->deal->valued_at, $this->deal->customer);

        return (new SlackMessage)->success()
                                 ->from("System")->image($logo)->content(trans('texts.notification_deal',
                ['total' => $total, 'customer' => $this->deal->customer->present()->name()]));
    }

}
