<?php

namespace App\Factory;

use App\Task;
use App\Account;
use App\User;
use App\Customer;

class TaskFactory
{
    public static function create(User $user, Account $account): Task
    {
        $task = new Task;
        $task->source_type = 1;
        $task->user_id = $user->id;
        $task->created_by = $user->id;
        $task->account_id = $account->id;

        return $task;
    }
}
