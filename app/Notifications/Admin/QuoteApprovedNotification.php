<?php

namespace App\Notifications\Admin;

use App\Mail\Admin\PaymentMade;
use App\Mail\Admin\QuoteApproved;
use App\Quote;
use App\Utils\Number;
use App\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class QuoteApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;


    /**
     * @var Quote
     */
    private Quote $quote;

    /**
     * @var string
     */
    private string $message_type;

    /**
     * QuoteApprovedNotification constructor.
     * @param Quote $quote
     * @param string $message_type
     */
    public function __construct(Quote $quote, $message_type = '')
    {
        $this->quote = $quote;
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
     * @return QuoteApproved
     */
    public function toMail($notifiable)
    {
        return new QuoteApproved($this->quote, $notifiable);
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
            'texts.notification_quote_approved_subject',
            [
                'total' => $this->quote->getFormattedTotal(),
                'quote' => $this->quote->getNumber(),
            ]
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
