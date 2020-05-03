<?php

namespace App\Factory;

use App\Task;
use App\Timer;
use App\Account;
use App\User;
use App\Customer;

class TimerFactory
{
    public static function create(User $user, Account $account, Task $task): Timer
    {
        $timer = new Timer;
        $timer->user_id = $user->id;
        $timer->task_id = $task->id;
        $timer->account_id = $account->id;

        return $timer;
    }
}
