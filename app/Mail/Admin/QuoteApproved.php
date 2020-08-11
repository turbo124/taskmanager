<?php

namespace App\Mail\Admin;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class QuoteApproved extends AdminMailer
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\Quote
     */
    private Quote $quote;

    /**
     * QuoteApproved constructor.
     * @param Quote $quote
     * @param \App\Models\User $user
     */
    public function __construct(Quote $quote, User $user)
    {
        $this->quote = $quote;
        $this->entity = $quote;
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
            'body'        => $this->message,
            'url'         => config('taskmanager.site_url') . 'portal/quotes/' . $this->quote->id,
            'button_text' => trans('texts.view_quote'),
            'signature'   => !empty($this->settings) ? $this->settings->email_signature : '',
            'logo'        => $this->quote->account->present()->logo(),
        ];
    }

    private function buildDataArray()
    {
        return [
            'total' => $this->quote->getFormattedTotal(),
            'quote' => $this->quote->getNumber(),
        ];
    }
}
