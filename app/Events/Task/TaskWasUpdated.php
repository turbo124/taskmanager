<?php

namespace App\Events\Task;

use App\Models\task;
use Illuminate\Queue\SerializesModels;
use App\Traits\SendSubscription;

/**
 * Class InvoiceWasMarkedSent.
 */
class TaskWasUpdated
{
    use SerializesModels;

    /**
     * @var task
     */
    public Task $task;

    /**
     * Create a new event instance.
     *
     * @param Task $task
     */
    public function __construct(task $task)
    {
        $this->task = $task;
        $this->send($task, get_class($this));
    }
}
