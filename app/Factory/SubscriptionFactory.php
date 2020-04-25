<?php

namespace App\Factory;

use App\Subscription;

class SubscriptionFactory
{
    public static function create(int $account_id, int $user_id): Subscription
    {
        $subscription = new Subscription;
        $subscription->account_id = $account_id;
        $subscription->user_id = $user_id;
        $subscription->target_url = '';
        $subscription->event_id = 1;
        $subscription->format = 'JSON';

        return $subscription;
    }
}
