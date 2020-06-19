<?php

namespace App\Factory;

use App\User;
use App\Account;
use Illuminate\Support\Str;

class CloneAccountToUserFactory
{
    public static function create(Account $account): User
    {
        $user = new User;
        $user->last_login = now();
        $user->ip = request()->ip();
        $user->confirmation_code = Str::random(config('taskmanager.key_length'));
        $user->domain_id = $account->domain_id;
        $user->is_active = 1;

        return $user;
    }
}
