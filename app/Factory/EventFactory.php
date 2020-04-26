<?php

namespace App\Factory;

use App\Event;
use App\Account;
use App\User;

class EventFactory
{
    public function create(User $user, Account $account): Event
    {
        $event = new Event;
        $event->created_by = $user->id;
        $event->event_type = 1;
        $event->account_id = $account->id;

        return $event;
    }
}
