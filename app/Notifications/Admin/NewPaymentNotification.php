<?php

namespace App\Notifications\Admin;

use App\Utils\Number;
use App\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class NewPaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private Payment $payment;

    private string $message_type;

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
       return !empty($this->message_type) ? [$this->message_type] : [$notifiable->account_user()->default_notification_type];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
       return (new MailMessage)->subject(trans('texts.notification_payment_paid_subject',
            ['customer' => $this->payment->customer->present()->name(),]))->markdown('email.admin.new', ['data' => [
            'title'     => trans('texts.notification_payment_paid_subject',
                ['customer' => $this->payment->customer->present()->name()]),
            'message'   => trans('texts.notification_payment_paid', [
                'total'  => $this->payment->getFormattedAmount(),
                'customer'  => $this->payment->customer->present()->name(),
                'invoice' => $this->payment->getFormattedInvoices(),
            ]),
            'signature' => isset($this->payment->account->settings->email_signature) ? $this->payment->account->settings->email_signature : '',
            'url'       => config('taskmanager.site_url') . 'portal/payments/' . $this->payment->id,
            'button_text'    => trans('texts.view_payment'),
            'logo'      => $this->payment->account->present()->logo(),
        ]]);


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

    public function toSlack($notifiable)
    {

        return (new SlackMessage)->success()
            ->from("System")->image($this->account->present()->logo())->content(trans('texts.notification_payment_paid', [
                'total'  => $this->payment->getFormattedAmount(),
                'customer'  => $this->payment->customer->present()->name(),
                'invoice' => $this->payment->getFormattedInvoices()
            ]));
    }

}
