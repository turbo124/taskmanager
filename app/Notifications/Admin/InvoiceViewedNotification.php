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

    protected $invitation;
    protected $invoice;
    protected $account;
    protected $contact;

    public function __construct($invitation)
    {
        $this->invoice = $invitation->invoice;
        $this->contact = $invitation->contact;
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
        $subject = trans('texts.notification_invoice_viewed_subject', [
            'customer'  => $this->contact->present()->name(),
            'invoice' => $this->invoice->number,
        ]);

        return (new MailMessage)->subject($subject)->markdown('email.admin.new', ['data' => [
            'title'     => $subject,
            'message'   => trans('texts.notification_invoice_viewed', [
                'total'  => $this->invoice->getFormattedTotal(),
                'customer'  => $this->contact->present()->name(),
                'invoice' => $this->invoice->number,
            ]),
            'url'       => config('taskmanager.site_url') . 'portal/invoices/' . $this->invoice->id,
            'button_text'    => trans('texts.view_invoice'),
            'signature' => !empty($this->invoice->account->settings) ? $this->invoice->account->settings->email_signature : '',
            'logo'      => $this->invoice->account->present()->logo(),
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
                'total'  => $this->invoice->getFormattedTotal(),
                'customer'  => $this->contact->present()->name(),
                'invoice' => $this->invoice->number,
            ]));
    }

}
