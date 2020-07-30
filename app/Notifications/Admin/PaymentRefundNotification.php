<?php

namespace App\Notifications\Admin;

use App\Mail\Admin\PaymentFailed;
use App\Mail\Admin\Refunded;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class PaymentRefundNotification extends Notification implements ShouldQueue
{
    use Queueable;


    /**
     * @var \App\Models\Payment
     */
    private Payment $payment;

    /**
     * @var string
     */
    private string $message_type;

    /**
     * NewPaymentNotification constructor.
     * @param \App\Models\Payment $payment
     * @param string $message_type
     */
    public function __construct(Payment $payment, $message_type = '')
    {
        $this->payment = $payment;
        $this->message_type = $message_type;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return !empty($this->message_type)
            ? [$this->message_type]
            : [
                $notifiable->account_user()->default_notification_type
            ];
    }

    /**
     * @param $notifiable
     * @return Refunded
     */
    public function toMail($notifiable)
    {
        return new Refunded($this->payment, $notifiable);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [//
        ];
    }

    private function getMessage()
    {
        $this->subject = trans(
            'texts.notification_refund_subject',
            ['customer' => $this->payment->customer->present()->name()]
        );
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)->success()
                                 ->from("System")->image($this->payment->account->present()->logo())->content(
                $this->getMessage()
            );
    }

}
