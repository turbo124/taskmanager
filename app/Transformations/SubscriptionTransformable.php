<?php

namespace App\Transformations;


use App\Subscription;

trait SubscriptionTransformable
{

    /**
     * @param Subscription $subscription
     * @return array
     */
    public function transform(Subscription $subscription)
    {
        $prod = new Subscription;
        $prod->id = (int)$subscription->id;
        $prod->account_id = (int)$subscription->account_id;
        $prod->user_id = (int)$subscription->user_id;
        $prod->updated_at = $subscription->updated_at;
        $prod->created_at = $subscription->created_at;
        $prod->is_deleted = (bool)$subscription->is_deleted;
        $prod->target_url = $subscription->target_url ? (string)$subscription->target_url : '';
        $prod->entity_id = (string)$subscription->entity_id;
        $prod->format = (string)$subscription->format;
        $prod->name = (string)$subscription->name;

        return $prod;
    }
}
