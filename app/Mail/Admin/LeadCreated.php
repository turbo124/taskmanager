<?php

namespace App\Mail\Admin;

use App\Lead;
use App\Payment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class LeadCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Lead
     */
    private Lead $lead;

    private $message;

    /**
     * @var array
     */
    private array $message_array;

    /**
     * @var User
     */
    private User $user;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Lead $lead, User $user)
    {
        $this->lead = $lead;
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
        $this->message = trans(
            'texts.notification_lead',
            $this->buildDataArray()

        );
    }

    private function setSubject()
    {
        $this->subject = trans(
            'texts.notification_lead_subject',
            $this->buildDataArray()
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
}
