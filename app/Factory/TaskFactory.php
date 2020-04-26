<?php

namespace App\Factory;

use App\Task;

class TaskFactory
{
    public static function create(int $user_id, int $account_id): Task
    {
        $task = new Task;
        $task->source_type = 1;
        $task->user_id = $user_id;
        $task->created_by = $user_id;
        $task->account_id = $account_id;

        return $task;
    }
}
