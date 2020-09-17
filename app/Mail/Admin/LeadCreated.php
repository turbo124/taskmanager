<?php

namespace App\Mail\Admin;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class LeadCreated extends AdminMailer
{
    use Queueable, SerializesModels;

    /**
     * @var Lead
     */
    private Lead $lead;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Lead $lead, User $user)
    {
        $this->lead = $lead;
        $this->entity = $lead;
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
            'texts.notification_lead_subject',
            $this->buildDataArray()
        );
    }

    private function buildDataArray()
    {
        return [
            'customer' => $this->lead->present()->name()
        ];
    }

    private function setMessage()
    {
        $this->message = trans(
            'texts.notification_lead',
            $this->buildDataArray()

        );
    }

    private function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'body'        => $this->message,
            'url'         => config('taskmanager.site_url') . 'portal/payments/' . $this->lead->id,
            'button_text' => trans('texts.view_deal'),
            'signature'   => isset($this->lead->account->settings->email_signature) ? $this->lead->account->settings->email_signature : '',
            'logo'        => $this->lead->account->present()->logo(),
        ];
    }
}
