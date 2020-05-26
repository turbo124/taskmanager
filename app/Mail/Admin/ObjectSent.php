<?php

namespace App\Mail\Admin;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class ObjectSent extends Mailable
{
    use Queueable, SerializesModels;

    private $invitation;
    private $entity;
    private string $entity_name;
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
        $this->invitation = $invitation;
        $this->entity_name = $entity_name;
        $this->entity = $invitation->{$entity_name};
        $this->contact = $invitation->contact;
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

    private function setSubject()
    {
        $this->subject = trans("texts.notification_{$this->entity_name}_sent_subject", $this->getDataArray());
    }

    private function setMessage()
    {
        $this->message = trans("texts.notification_{$this->entity_name}_sent", $this->getDataArray());
    }

    private function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'message'     => $this->message,
            'url'         => $this->invitation->getLink() . '?silent=true',
            'button_text' => trans("texts.view_{$this->entity_name}"),
            'signature'   => $this->invitation->account->settings->email_signature,
            'logo'        => $this->invitation->account->present()->logo(),
        ];
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
