<?php

namespace App\Notifications\Admin;

use App\Utils\Number;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EntitySentNotification extends Notification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $invitation;
    private $entity;
    private string $entity_name;
    private $contact;
    private string $message_type;

    public function __construct($invitation, $entity_name, string $message_type = '')
    {
        $this->invitation = $invitation;
        $this->entity_name = $entity_name;
        $this->entity = $invitation->{$entity_name};
        $this->contact = $invitation->contact;
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
         return trans("texts.notification_{$this->entity_name}_sent_subject", $this->getDataArray());
    }

    private function buildMessage()
    {
         return [
                    'title'       => $subject,
                    'message'     => trans("texts.notification_{$this->entity_name}_sent", $this->getDataArray()),
                    'url'         => $this->invitation->getLink() . '?silent=true',
                    'button_text' => trans("texts.view_{$this->entity_name}"),
                    'signature'   => $this->invitation->account->settings->email_signature,
                    'logo'        => $this->invitation->account->present()->logo(),
                ]
    }

    private function getDataArray()
    {
        return [
            'total'    => $this->entity->getFormattedTotal(),
            'customer' => $this->contact->present()->name(),
            'invoice'  => $this->entity->getNumber(),
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
        return [];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)->from(trans('texts.from_slack'))->success()
                                 ->image($this->entity->account->present()->logo)
                                 ->content(
                                     $this->buildSubject()
                                 )->attachment(
                function ($attachment) {
                    $attachment->title(
                        trans('texts.invoice_number_here', ['invoice' => $this->entity->number]),
                        $this->invitation->getLink() . '?silent=true'
                    )->fields(
                        [
                            trans('texts.customer') => $this->contact->present()->name(),
                            trans('texts.total')    => $this->entity->getFormattedTotal(),
                        ]
                    );
                }
            );
    }

}
