<?php

namespace App\Mail\Admin;

use App\Models\Deal;
use App\Models\User;
use App\Traits\Money;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class DealCreated extends AdminMailer
{
    use Queueable, SerializesModels, Money;

    private Deal $deal;

    /**
     * TaskCreated constructor.
     * @param Deal $deal
     * @param User $user
     */
    public function __construct(Deal $deal, User $user)
    {
        $this->deal = $deal;
        $this->entity = $deal;
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
            'texts.notification_deal',
            $this->buildDataArray()

        );
    }

    private function setSubject()
    {
        $this->subject = trans(
            'texts.notification_deal_subject',
            $this->buildDataArray()
        );
    }

    private function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'body'        => $this->message,
            'url'         => config('taskmanager.site_url') . 'portal/payments/' . $this->deal->id,
            'button_text' => trans('texts.view_deal'),
            'signature'   => !empty($this->settings) ? $this->settings->email_signature : '',
            'logo'        => $this->deal->account->present()->logo(),
        ];
    }

    private function buildDataArray()
    {
        return [
            'total'    => $this->formatCurrency($this->deal->valued_at, $this->deal->customer),
            'customer' => $this->deal->customer->present()->name()
        ];
    }
}
