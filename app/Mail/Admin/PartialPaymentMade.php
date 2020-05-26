<?php

namespace App\Mail\Admin;

use App\Payment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class PartialPaymentMade extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Payment
     */
    private Payment $payment;

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
     * PaymentMade constructor.
     * @param Payment $payment
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

    private function setMessage()
    {
        $this->message = trans('texts.notification_partial_payment_paid', $this->getDataArray());
    }

    private function setSubject()
    {
        $this->subject = trans(
            'texts.notification_partial_payment_paid_subject',
            ['customer' => $this->payment->customer->present()->name()]
        );
    }

    private function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'message'     => $this->message,
            'url'         => config('taskmanager.site_url') . '/payments/' . $this->payment->id,
            'button_text' => trans('texts.view_payment'),
            'signature'   => isset($this->payment->account->settings->email_signature) ? $this->payment->account->settings->email_signature : '',
            'logo'        => $this->payment->account->present()->logo(),
        ];
    }

    private function getDataArray()
    {
        return [
            'total'    => $this->payment->getFormattedAmount(),
            'customer' => $this->payment->customer->present()->name(),
            'invoice'  => $this->payment->getFormattedInvoices(),
        ];
    }
}
