<?php

namespace App\Transformations;

use App\Task;
use App\User;
use App\Repositories\UserRepository;
use App\Repositories\TaskStatusRepository;
use App\TaskStatus;
use App\Transformations\TimerTransformable;
use App\Timer;
use App\Transformations\CustomerTransformable;
use App\Libraries\Utils;

trait TaskTransformable
{
    use CustomerTransformable;

    /**
     * @param Task $task
     * @return array
     * @throws \Exception
     */
    protected function transformTask(Task $task)
    {

        return [
            'id'                     => (int)$task->id,
            'customer_name'          => $task->customer->present()->name,
            'title'                  => $task->title,
            'content'                => $task->content,
            'comments'               => $task->comments,
            'due_date'               => $task->due_date,
            'start_date'             => $task->start_date,
            'is_completed'           => $task->is_completed,
            'task_status'            => $task->task_status,
            'status_name'            => $task->taskStatus->title,
            'task_type'              => $task->task_type,
            'deleted_at'             => $task->deleted_at,
            'rating'                 => $task->rating,
            'customer'               => $this->transformCustomer($task->customer),
            'customer_id'            => $task->customer_id,
            'valued_at'              => $task->valued_at,
            'source_type'            => $task->source_type,
            'users'                  => $task->users,
            'contributors'           => $task->users()->pluck('user_id')->all(),
            'is_active'              => $task->is_active,
            'project_id'             => $task->project_id,
            'is_deleted'             => (bool)$task->is_deleted,
            'timers'                 => $this->transformTimers($task->timers),
            'custom_value1'          => $task->custom_value1 ?: '',
            'custom_value2'          => $task->custom_value2 ?: '',
            'custom_value3'          => $task->custom_value3 ?: '',
            'custom_value4'          => $task->custom_value4 ?: '',
            'public_notes'           => $task->public_notes ?: '',
            'private_notes'          => $task->private_notes ?: '',
            'task_status_sort_order' => (int)$task->task_status_sort_order,
        ];
    }

    /**
     * @param $invitations
     * @return array
     */
    private function transformTimers($timers)
    {

        if ($timers->count() === 0) {
            return [];
        }

        return $timers->map(function (Timer $timer) {
            return (new TimerTransformable)->transform($timer);
        })->all();
    }

}
