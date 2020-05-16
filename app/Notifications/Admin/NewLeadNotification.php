<?php

namespace App\Notifications\Admin;

use App\Utils\Number;
use App\Lead;
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

    private Lead $lead;

    private string $message_type;

    public function __construct(Lead $lead, string $message_type = '')
    {
        $this->lead = $lead;
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

        return (new MailMessage)->subject(
           $this->subject
        )->markdown(
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

    private function setMessage()
    {
        $this->message = trans(
                        'texts.notification_lead', $this->buildDataArray()
                       
                    );
    }

    private function setSubject()
    {
        $this->subject = trans(
                'texts.notification_lead_subject', $this->buildSubject()
            );
    }

    private function buildMessage()
    {
        $this->message_array = [
                    'title'       => $this->subject,
                    'message'     => $this->message,
                    'url'         => config('taskmanager.site_url') . 'portal/payments/' . $this->lead->id,
                    'button_text' => trans('texts.view_deal'),
                    'signature'   => isset($this->lead->account->settings->email_signature) ? $this->lead->account->settings->email_signature : '',
                    'logo'        => $this->lead->account->present()->logo(),
                ];
    }

    private function buildDataArray()
    {
        return [
                            'customer' => $this->lead->present()->name()
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
        $logo = $this->lead->account->present()->logo();

        return (new SlackMessage)->success()
                                 ->from("System")->image($logo)->content(
               $this->subject
            );
    }

}
