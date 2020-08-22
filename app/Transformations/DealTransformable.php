
<?php

namespace App\Transformations;

use App\Libraries\Utils;
use App\Models\Deal;
use Exception;

trait DealTransformable
{
    

    /**
     * @param Task $task
     * @return array
     * @throws Exception
     */
    protected function transformDeal(Deal $task)
    {
        return [
            'id'                     => (int)$task->id,
            'title'                  => $task->title,
            'description'            => $task->description,
            'comments'               => $task->comments,
            'due_date'               => $task->due_date,
            'task_status'            => (int)$task->task_status,
            'deleted_at'             => $task->deleted_at,
            'rating'                 => $task->rating,
            'customer_id'            => $task->customer_id,
            'valued_at'              => $task->valued_at,
            'source_type'            => $task->source_type,
            'is_deleted'             => (bool)$task->is_deleted,
            'custom_value1'          => $task->custom_value1 ?: '',
            'custom_value2'          => $task->custom_value2 ?: '',
            'custom_value3'          => $task->custom_value3 ?: '',
            'custom_value4'          => $task->custom_value4 ?: '',
            'public_notes'           => $task->public_notes ?: '',
            'private_notes'          => $task->private_notes ?: '',
           
        ];
    }
}
