<?php

namespace App\Notifications\Admin;

use App\Mail\Admin\QuoteRejected;
use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class QuoteRejectedNotification extends Notification implements ShouldQueue
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
     * @return QuoteRejected
     */
    public function toMail($notifiable)
    {
        return new QuoteRejected($this->quote, $notifiable);
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
                                 ->from("System")->image($this->quote->account->present()->logo())->content(
                $this->getMessage()
            );
    }

    private function getMessage()
    {
        $this->subject = trans(
            'texts.notification_quote_rejected_subject',
            [
                'total' => $this->quote->getFormattedTotal(),
                'quote' => $this->quote->getNumber(),
            ]
        );
    }

}
