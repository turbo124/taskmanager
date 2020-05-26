<?php

namespace App\Notifications\Admin;

use App\Mail\Admin\PartialPaymentMade;
use App\Utils\Number;
use App\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class NewPartialPaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Payment
     */
    private Payment $payment;

    /**
     * @var string
     */
    private string $message_type;

    /**
     * NewPartialPaymentNotification constructor.
     * @param Payment $payment
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
     * @return PartialPaymentMade
     */
    public function toMail($notifiable)
    {
        return new PartialPaymentMade($this->payment, $notifiable);
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
            'texts.notification_partial_payment_paid_subject',
            ['customer' => $this->payment->customer->present()->name()]
        );
    }

    public function toSlack($notifiable)
    {
        $logo = $this->payment->account->present()->logo();

        return (new SlackMessage)->success()
                                 ->from("System")->image($logo)->content(
                $this->getMessage()
            );
    }

}
