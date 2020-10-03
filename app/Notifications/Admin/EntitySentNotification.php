<?php

namespace App\Notifications\Admin;

use App\Mail\Admin\ObjectSent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
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

    /**
     * @var string
     */
    private string $entity_name;
    private $contact;

    /**
     * @var string
     */
    private string $message_type;

    /**
     * EntitySentNotification constructor.
     * @param $invitation
     * @param $entity_name
     * @param string $message_type
     */
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
     * @param $notifiable
     * @return ObjectSent
     */
    public function toMail($notifiable)
    {
        return new ObjectSent($this->invitation, $notifiable);
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
                                     trans(
                                         "texts.notification_{$this->entity_name}_sent_subject",
                                         $this->getDataArray()
                                     )
                                 );
    }

    private function getDataArray()
    {
        return [
            'total'    => $this->entity->getFormattedTotal(),
            'customer' => $this->contact->present()->name(),
            'invoice'  => $this->entity->getNumber(),
        ];
    }

}
