<?php

namespace App\Mail\Admin;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class PaymentMade extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\Payment
     */
    private Payment $payment;

    /**
     * @var \App\Models\User
     */
    private User $user;

    private $message;

    /**
     * @var array
     */
    private array $message_array;


    /**
     * PaymentMade constructor.
     * @param \App\Models\Payment $payment
     * @param User $user
     */
    public function __construct(Payment $payment, User $user)
    {
        $this->payment = $payment;
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
        $this->subject = trans(
            'texts.notification_payment_paid_subject',
            ['customer' => $this->payment->customer->present()->name(),]
        );
    }

    private function setMessage()
    {
        $this->message = trans('texts.notification_payment_paid', $this->getDataArray());
    }

    private function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'message'     => $this->message,
            'signature'   => isset($this->payment->account->settings->email_signature) ? $this->payment->account->settings->email_signature : '',
            'url'         => config('taskmanager.site_url') . 'portal/payments/' . $this->payment->id,
            'button_text' => trans('texts.view_payment'),
            'logo'        => $this->payment->account->present()->logo(),
        ];
    }

    private function getDataArray()
    {
        return [
            'total'    => $this->payment->getFormattedTotal(),
            'customer' => $this->payment->customer->present()->name(),
            'invoice'  => $this->payment->getFormattedInvoices(),
        ];
    }
}
