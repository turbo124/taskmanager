<?php

namespace App\Factory;

use App\Subscription;
use App\Account;
use App\User;

class SubscriptionFactory
{
    public static function create(Account $account, User $user): Subscription
    {
        $subscription = new Subscription;
        $subscription->account_id = $account->id;
        $subscription->user_id = $user->id;
        $subscription->event_id = 1;
        $subscription->format = 'JSON';

        return $subscription;
    }
}
