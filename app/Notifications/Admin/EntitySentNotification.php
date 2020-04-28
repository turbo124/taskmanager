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

    protected $invitation;
    protected $entity;
    protected $entity_name;
    protected $contact;

    public function __construct($invitation, $entity_name)
    {
        $this->invitation = $invitation;
        $this->entity_name = $entity_name;
        $this->entity = $invitation->{$entity_name};
        $this->contact = $invitation->contact;
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
        $subject = trans("texts.notification_{$this->entity_name}_sent_subject", [
            'customer'  => $this->contact->present()->name(),
            'invoice' => $this->entity->number,
        ]);

        return (new MailMessage)->subject($subject)->markdown('email.admin.new', 
    [
        'data' => [
            'title'     => $subject,
            'message'   => trans("texts.notification_{$this->entity_name}_sent", [
                'total'  => $this->entity->getFormattedTotal(),
                'customer'  => $this->contact->present()->name(),
                'invoice' => $this->entity->number,
            ]),
            'url'       => $this->invitation->getLink() . '?silent=true',
            'button_text'    => trans("texts.view_{$this->entity_name}"),
            'signature' => $this->invitation->account->settings->email_signature,
            'logo'      => $this->invitation->account->present()->logo(),
        ]
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

    public function toSlack($notifiable)
    {        
        return (new SlackMessage)->from(trans('texts.from_slack'))->success()
            ->image($this->entity->account->present()->logo)
            ->content(trans("texts.notification_{$this->entity_name}_sent_subject", [
                'total'  => $this->entity->getFormattedTotal(),
                'customer'  => $this->contact->present()->name(),
                'invoice' => $this->entity->number
            ]))->attachment(function ($attachment) use ($total) {
                $attachment->title(trans('texts.invoice_number_here', ['invoice' => $this->entity->number]),
                    $this->invitation->getLink() . '?silent=true')->fields([
                    trans('texts.customer') => $this->contact->present()->name(),
                    trans('texts.total') => $this->entity->getFormattedTotal(),
                ]);
            });
    }

}
