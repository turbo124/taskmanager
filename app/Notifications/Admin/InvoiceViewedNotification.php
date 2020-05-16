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
        return !empty($this->message_type)
            ? [$this->message_type]
            : [
                $notifiable->account_user()->default_notification_type
            ];
    }
 
    private function setMessage()
    {
        $this->message = trans('texts.notification_invoice_viewed', $this->buildDataArray());
    }

    private function build()
    {
        $this->setSubject();
        $this->setMessage();
        $this->buildMessage();
    }

    private function setSubject()
    {
        $this->subject = trans(
            'texts.notification_invoice_viewed_subject', $this->buildDataArray()
        );
    }

    private function buildMessage()
    {
        $this->message_array = [
                    'title'       => $this->subject,
                    'message'     => $this->message,
                    'url'         => config('taskmanager.site_url') . 'portal/invoices/' . $this->invoice->id,
                    'button_text' => trans('texts.view_invoice'),
                    'signature'   => !empty($this->invoice->account->settings) ? $this->invoice->account->settings->email_signature : '',
                    'logo'        => $this->invoice->account->present()->logo(),
                ];
    }

    private function buildDataArray()
    {
          return [
                            'total'    => $this->invoice->getFormattedTotal(),
                            'customer' => $this->contact->present()->name(),
                            'invoice'  => $this->invoice->number,
                        ];
    }


    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $this->build();

        return (new MailMessage)->subject($this->subject)->markdown(
            'email.admin.new',
            [
                'data' => $this->message_array
            ]
        );
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
        $this->build();

        return (new SlackMessage)->success()->from(trans('texts.from_slack'))->image($logo)
                                 ->content(
                                    $this->subject
                                 );
    }

}
