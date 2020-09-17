<?php

namespace App\Mail\Admin;

use App\Models\Task;
use App\Models\User;
use App\Traits\Money;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class TaskCreated extends AdminMailer
{
    use Queueable, SerializesModels, Money;

    private Task $task;

    /**
     * TaskCreated constructor.
     * @param Task $task
     * @param User $user
     */
    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
        $this->entity = $task;
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
        $this->execute();
    }

    private function setSubject()
    {
        $this->subject = trans(
            'texts.notification_deal_subject',
            $this->buildDataArray()
        );
    }

    private function buildDataArray()
    {
        return [
            'total'    => $this->formatCurrency($this->task->valued_at, $this->task->customer),
            'customer' => $this->task->customer->present()->name()
        ];
    }

    private function setMessage()
    {
        $this->message = trans(
            'texts.notification_deal',
            $this->buildDataArray()

        );
    }

    private function buildMessage()
    {
        $this->message_array = [
            'title'       => $this->subject,
            'body'        => $this->message,
            'url'         => config('taskmanager.site_url') . 'portal/payments/' . $this->task->id,
            'button_text' => trans('texts.view_deal'),
            'signature'   => !empty($this->settings) ? $this->settings->email_signature : '',
            'logo'        => $this->task->account->present()->logo(),
        ];
    }
}
