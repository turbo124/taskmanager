<?php

namespace App\Transformations;


use App\Cases;
use App\Subscription;

trait CaseTransformable
{

    /**
     * @param Subscription $subscription
     * @return array
     */
    public function transform(Cases $cases)
    {
        return [
            'id'            => (int)$cases->id,
            'message'       => $cases->message,
            'subject'       => $cases->subject,
            'private_notes' => $cases->private_notes,
            'due_date'      => $cases->due_date,
            'account_id'    => (int)$cases->account_id,
            'customer_id'   => (int)$cases->customer_id,
            'user_id'       => (int)$cases->user_id,
            'status_id'     => (int)$cases->status_id,
            'category_id'   => (int)$cases->category_id,
            'priority_id'   => (int)$cases->priority_id,
            'updated_at'    => $cases->updated_at,
            'created_at'    => $cases->created_at,
            'is_deleted'    => (bool)$cases->is_deleted
        ];
    }
}
