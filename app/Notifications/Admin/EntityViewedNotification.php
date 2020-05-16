<?php

namespace App\Notifications\Admin;

use App\Utils\Number;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class EntityViewedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     * @
     */

    private $invitation;
    private string $entity_name;
    private $entity;
    private $contact;
    private string $message_type;

    public function __construct($invitation, $entity_name, string $message_type = '')
    {
        $this->entity_name = $entity_name;
        $this->entity = $invitation->{$entity_name};
        $this->contact = $invitation->contact;
        $this->invitation = $invitation;
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

    private function setMessage()
    {
        $this->message = trans("texts.notification_{$this->entity_name}_viewed", $this->getDataArray())
    }

    private function setSubject()
    {
         $this->subject = trans(
            "texts.notification_{$this->entity_name}_viewed_subject",
            [
                'customer'         => $this->contact->present()->name(),
                $this->entity_name => $this->entity->number,
            ]
        );

        return $subject;
    }

    private function build()
    {
        $this->setSubject();
        $this->setMessage();
        $this->buildMessage();
    }

    public function buildMessage()
    {
           $this->message_array = [
                        'title'       => $this->subject,
                        'message'     => $this->message,
                        'url'         => config(
                                'taskmanager.site_url'
                            ) . "/portal/{$this->entity_name}/" . $this->invitation->key .
                            "?silent=true",
                        'button_text' => trans("texts.view_{$this->entity_name}"),
                        'signature'   => isset($this->entity->account->settings->email_signature) ? $this->entity->account->settings->email_signature : '',
                        'logo'        => $this->entity->account->present()->logo(),
                    ];
    }

    private function getDataArray()
    {
        return [
            'total'            => $this->entity->getFormattedTotal(),
            'customer'         => $this->contact->present()->name(),
            $this->entity_name => $this->entity->getNumber()
        ];
    }

    public function toSlack($notifiable)
    {
        $this->build();
        return (new SlackMessage)->from(trans('texts.from_slack'))->success()
                                 ->content(
                                    $this->subject
                                 );
    }
}
