<?php

namespace App\Factory;

use App\User;
use Illuminate\Support\Str;

class UserFactory
{
    public static function create(int $domain_id): User
    {
        $user = new User;
        $user->first_name = '';
        $user->last_login = now();
        $user->ip = request()->ip();
        $user->confirmation_code = Str::random(config('taskmanager.key_length'));
        $user->domain_id = $domain_id;
        $user->last_name = '';
        $user->phone_number = '';
        $user->username = '';
        $user->email = '';
        $user->gender = '';
        $user->dob = null;
        $user->job_description = '';
        $user->is_active = 1;

        return $user;
    }
}
