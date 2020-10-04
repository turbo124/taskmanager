<?php

namespace App\Mail\Admin;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class ObjectViewed extends AdminMailer
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
        $this->entity_name = $entity_name;
        $this->entity = $invitation->inviteable;
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
        $this->execute();
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

    private function setMessage()
    {
        $this->message = trans("texts.notification_{$this->entity_name}_viewed", $this->getDataArray());
    }

    private function getDataArray()
    {
        return [
            'total'            => $this->entity->getFormattedTotal(),
            'customer'         => $this->contact->present()->name(),
            $this->entity_name => $this->entity->getNumber()
        ];
    }

    public function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'body'        => $this->message,
            'url'         => config(
                    'taskmanager.site_url'
                ) . "/portal/view/{$this->entity_name}/" . $this->invitation->key .
                "?silent=true",
            'button_text' => trans("texts.view_{$this->entity_name}"),
            'signature'   => isset($this->entity->account->settings->email_signature) ? $this->entity->account->settings->email_signature : '',
            'logo'        => $this->entity->account->present()->logo(),
        ];
    }
}
