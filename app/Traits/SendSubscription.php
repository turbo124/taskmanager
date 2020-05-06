<?php


namespace App\Traits;


use App\Subscription;

trait SendSubscription
{
    public function send($entity, $event)
    {
        $event_name = (new \ReflectionClass($event))->getShortName();
        $class = new \ReflectionClass(Subscription::class);
        $value = $class->getConstant(strtoupper($event_name));

        \App\Jobs\Subscription\SendSubscription::dispatchNow($entity, $value);
    }
}