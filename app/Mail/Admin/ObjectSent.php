<?php

namespace App\Mail\Admin;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class ObjectSent extends AdminMailer
{
    use Queueable, SerializesModels;

    private $invitation;
    private string $entity_name;
    private $contact;

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
        $this->execute();
    }

    private function setSubject()
    {
        $this->subject = trans("texts.notification_{$this->entity_name}_sent_subject", $this->getDataArray());
    }

    private function getDataArray()
    {
        return [
            'total'    => $this->entity->getFormattedTotal(),
            'customer' => $this->contact->present()->name(),
            'invoice'  => $this->entity->getNumber(),
        ];
    }

    private function setMessage()
    {
        $this->message = trans("texts.notification_{$this->entity_name}_sent", $this->getDataArray());
    }

    private function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'body'        => $this->message,
            'url'         => config(
                    'taskmanager.site_url'
                ) . "/portal/view/{$this->entity_name}/" . $this->invitation->key .
                "?silent=true",
            'button_text' => trans("texts.view_{$this->entity_name}"),
            'signature'   => $this->invitation->account->settings->email_signature,
            'logo'        => $this->invitation->account->present()->logo(),
        ];
    }
}
