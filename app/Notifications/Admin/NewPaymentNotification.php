<?php

namespace App\Notifications\Admin;

use App\Utils\Number;
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

    protected $payment;

    public function __construct($payment)
    {
        $this->payment = $payment;
    }


     /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return isset($this->entity->account->settings->slack_enabled) && $this->entity->account->settings->slack_enabled === true ? ['mail', 'slack'] : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $invoice_numbers = trans('texts.invoice_number_abbreviated');

        foreach ($this->payment->invoices as $invoice) {
            $invoice_numbers .= $invoice->number . ',';
        }

        $invoice_numbers = substr($invoice_numbers, 0, -1);

        return (new MailMessage)->subject(trans('texts.notification_payment_paid_subject',
            ['customer' => $this->payment->customer->present()->name(),]))->markdown('email.admin.new', ['data' => [
            'title'     => trans('texts.notification_payment_paid_subject',
                ['customer' => $this->payment->customer->present()->name()]),
            'message'   => trans('texts.notification_payment_paid', [
                'total'  => Number::formatMoney($this->payment->amount, $this->payment->customer),
                'customer'  => $this->payment->customer->present()->name(),
                'invoice' => $invoice_numbers,
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
       
        $invoice_texts = trans('texts.invoice_number_abbreviated');

        foreach ($this->payment->invoices as $invoice) {
            $invoice_texts .= $invoice->number . ',';
        }

        $invoice_texts = substr($invoice_texts, 0, -1);

        return (new SlackMessage)->success()
            //->to("#devv2")
            ->from("System")->image($this->account->present()->logo())->content(trans('texts.notification_payment_paid', [
                'total'  => Number::formatMoney($this->payment->total, $this->payment->customer),
                'customer'  => $this->payment->customer->present()->name(),
                'invoice' => $invoice_texts
            ]));
    }

}
