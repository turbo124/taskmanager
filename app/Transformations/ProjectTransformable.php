<?php

namespace App\Transformations;

use App\Project;

trait ProjectTransformable
{
    /**
     * @param Project $project
     * @return array
     */
    protected function transformProject(Project $project)
    {
        return [
            'id'               => (int)$project->id,
            'customer_name'    => $project->customer->present()->name,
            'title'            => $project->title,
            'description'      => $project->description,
            'is_completed'     => $project->is_completed,
            'due_date'         => $project->due_date,
            'customer_id'      => $project->customer_id,
            'updated_at'       => (int)$project->updated_at,
            'deleted_at'       => $project->deleted_at,
            'created_at'       => $project->created_at,
            'is_deleted'       => (bool)$project->is_deleted,
            'task_rate'        => (float)$project->task_rate,
            'budgeted_hours'   => (float)$project->budgeted_hours,
            'account_id'       => $project->account_id,
            'user_id'          => $project->user_id,
            'assigned_user_id' => $project->assigned_user_id,
            'notes'            => $project->notes,
            'custom_value1'    => $project->custom_value1 ?: '',
            'custom_value2'    => $project->custom_value2 ?: '',
            'custom_value3'    => $project->custom_value3 ?: '',
            'custom_value4'    => $project->custom_value4 ?: '',
        ];
    }

}
