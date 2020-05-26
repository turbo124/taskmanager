<?php

namespace App\Mail\Admin;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class ObjectViewed extends Mailable
{
    use Queueable, SerializesModels;

    private $invitation;
    private string $entity_name;
    private $entity;
    private $contact;
    private $message;

    /**
     * @var User
     */
    private User $user;

    /**
     * @var array
     */
    private array $message_array;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invitation, $entity_name, User $user)
    {
        $this->entity_name = $entity_name;
        $this->entity = $invitation->{$entity_name};
        $this->contact = $invitation->contact;
        $this->invitation = $invitation;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->setSubject();
        $this->setMessage();
        $this->buildMessage();

        return $this->to($this->user->email)
                    ->from('tamtamcrm@support.com')
                    ->subject($this->subject)
                    ->markdown(
                        'email.admin.new',
                        [
                            'data' => $this->message_array
                        ]
                    );
    }

    private function setMessage()
    {
        $this->message = trans("texts.notification_{$this->entity_name}_viewed", $this->getDataArray());
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
}
