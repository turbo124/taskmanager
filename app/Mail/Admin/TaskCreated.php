<?php

namespace App\Mail\Admin;

use App\Order;
use App\Task;
use App\User;
use App\Utils\Number;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskCreated extends Mailable
{
    use Queueable, SerializesModels;

    private Task $task;

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
     * TaskCreated constructor.
     * @param Task $task
     * @param User $user
     */
    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
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
        $this->message = trans(
            'texts.notification_deal',
            $this->buildDataArray()

        );
    }

    private function setSubject()
    {
        $this->subject = trans(
            'texts.notification_deal_subject',
            $this->buildDataArray()
        );
    }

    private function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'message'     => $this->message,
            'url'         => config('taskmanager.site_url') . 'portal/payments/' . $this->task->id,
            'button_text' => trans('texts.view_deal'),
            'signature'   => !empty($this->settings) ? $this->settings->email_signature : '',
            'logo'        => $this->task->account->present()->logo(),
        ];
    }

    private function buildDataArray()
    {
        return [
            'total'    => Number::formatCurrency($this->task->valued_at, $this->task->customer),
            'customer' => $this->task->customer->present()->name()
        ];
    }
}
