<?php

namespace App\Factory;

use App\GroupSetting;
use App\Account;
use App\User;

class GroupSettingFactory
{
    public static function create(Account $account, User $user): GroupSetting
    {
        $gs = new GroupSetting;
        $gs->name = '';
        $gs->account_id = $account->id;
        $gs->user_id = $user->id;
        $gs->settings = '{}';

        return $gs;
    }
}
