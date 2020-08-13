<?php

namespace App\Transformations;

use App\Models\Department;
use App\Models\Group;
use App\Repositories\UserRepository;
use App\Models\User;
use stdClass;

trait GroupTransformable
{
    /**
     * @param Group $group_setting
     * @return array
     */
    protected function transformGroup(Group $group)
    {
        return [
            'id'         => $group->id,
            'created_at' => $group->created_at,
            'deleted_at' => $group->deleted_at,
            'name'       => (string)$group->name ?: '',
            'settings'   => $group->settings ?: new stdClass,
        ];
    }
}
