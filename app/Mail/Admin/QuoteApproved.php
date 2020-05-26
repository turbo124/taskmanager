<?php

namespace App\Mail\Admin;

use App\Quote;
use App\User;
use App\Utils\Number;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Quote
     */
    private Quote $quote;

    /**
     * @var User
     */
    private User $user;

    private $message;

    /**
     * @var array
     */
    private array $message_array;

    /**
     * QuoteApproved constructor.
     * @param Quote $quote
     * @param User $user
     */
    public function __construct(Quote $quote, User $user)
    {
        $this->quote = $quote;
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
            'texts.notification_quote_approved',
            $this->buildDataArray()

        );
    }

    private function setSubject()
    {
        $this->subject = trans(
            'texts.notification_quote_approved_subject',
            $this->buildDataArray()
        );
    }

    private function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'message'     => $this->message,
            'url'         => config('taskmanager.site_url') . 'portal/quotes/' . $this->quote->id,
            'button_text' => trans('texts.view_quote'),
            'signature'   => !empty($this->settings) ? $this->settings->email_signature : '',
            'logo'        => $this->quote->account->present()->logo(),
        ];
    }

    private function buildDataArray()
    {
        return [
            'total'    => $this->quote->getFormattedTotal(),
            'quote'  => $this->quote->getNumber(),
        ];
    }
}
