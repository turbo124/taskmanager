<?php

namespace App\Notifications\Admin;

use App\Utils\Number;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class InvoiceViewedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $invitation;
    private $invoice;
    private $account;
    private $contact;
    private string $message_type;

    public function __construct($invitation, string $message_type = '')
    {
        $this->invoice = $invitation->invoice;
        $this->contact = $invitation->contact;
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
        $subject = trans('texts.notification_invoice_viewed_subject', [
            'customer' => $this->contact->present()->name(),
            'invoice'  => $this->invoice->number,
        ]);

        return (new MailMessage)->subject($subject)->markdown('email.admin.new', ['data' => [
            'title'       => $subject,
            'message'     => trans('texts.notification_invoice_viewed', [
                'total'    => $this->invoice->getFormattedTotal(),
                'customer' => $this->contact->present()->name(),
                'invoice'  => $this->invoice->number,
            ]),
            'url'         => config('taskmanager.site_url') . 'portal/invoices/' . $this->invoice->id,
            'button_text' => trans('texts.view_invoice'),
            'signature'   => !empty($this->invoice->account->settings) ? $this->invoice->account->settings->email_signature : '',
            'logo'        => $this->invoice->account->present()->logo(),
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
        return (new SlackMessage)->success()->from(trans('texts.from_slack'))->image($logo)
                                 ->content(trans('texts.notification_invoice_viewed', [
                                     'total'    => $this->invoice->getFormattedTotal(),
                                     'customer' => $this->contact->present()->name(),
                                     'invoice'  => $this->invoice->number,
                                 ]));
    }

}
