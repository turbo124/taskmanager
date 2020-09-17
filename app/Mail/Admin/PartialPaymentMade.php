<?php

namespace App\Mail\Admin;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class PartialPaymentMade extends AdminMailer
{
    use Queueable, SerializesModels;

    /**
     * @var Payment
     */
    private Payment $payment;


    /**
     * PaymentMade constructor.
     * @param Payment $payment
     * @param User $user
     */
    public function __construct(Payment $payment, User $user)
    {
        $this->payment = $payment;
        $this->entity = $payment;
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
            'texts.notification_partial_payment_paid_subject',
            ['customer' => $this->payment->customer->present()->name()]
        );
    }

    private function setMessage()
    {
        $this->message = trans('texts.notification_partial_payment_paid', $this->getDataArray());
    }

    private function getDataArray()
    {
        return [
            'total'    => $this->payment->getFormattedTotal(),
            'customer' => $this->payment->customer->present()->name(),
            'invoice'  => $this->payment->getFormattedInvoices(),
        ];
    }

    private function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'body'        => $this->message,
            'url'         => config('taskmanager.site_url') . '/payments/' . $this->payment->id,
            'button_text' => trans('texts.view_payment'),
            'signature'   => isset($this->payment->account->settings->email_signature) ? $this->payment->account->settings->email_signature : '',
            'logo'        => $this->payment->account->present()->logo(),
        ];
    }
}
