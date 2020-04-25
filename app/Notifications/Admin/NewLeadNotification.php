<?php

namespace App\Notifications\Admin;

use App\Utils\Number;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class NewLeadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    protected $lead;
    protected $account;

    public function __construct($lead, $account)
    {
        $this->lead = $lead;
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
        return (new MailMessage)->subject(trans('texts.notification_lead_subject',
            ['customer' => $this->lead->present()->name]))->markdown('email.admin.new', ['data' =>  [
            'title'     => trans('texts.notification_lead_subject', ['customer' => $this->lead->present()->name()]),
            'message'   => trans('texts.notification_lead', [
                'customer' => $this->lead->present()->name()
            ]),
            'url'       => config('taskmanager.site_url') . 'portal/payments/' . $this->lead->id,
            'button_text'    => trans('texts.view_deal'),
            'signature' => isset($this->lead->account->settings->email_signature) ? $this->lead->account->settings->email_signature : '',
            'logo'      => $this->lead->account->present()->logo(),
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
        $logo = $this->lead->account->present()->logo();

        return (new SlackMessage)->success()
            ->from("System")->image($logo)->content(trans('texts.notification_deal',
                ['customer' => $this->lead->present()->name()]));
    }

}
