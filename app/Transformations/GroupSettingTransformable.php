<?php

namespace App\Transformations;

use App\Department;
use App\GroupSetting;
use App\Repositories\UserRepository;
use App\User;
use stdClass;

trait GroupSettingTransformable
{
    /**
     * @param GroupSetting $group_setting
     * @return array
     */
    protected function transformGroupSetting(GroupSetting $group_setting)
    {
        return [
            'id'         => $group_setting->id,
            'created_at' => $group_setting->created_at,
            'deleted_at' => $group_setting->deleted_at,
            'name'       => (string)$group_setting->name ?: '',
            'settings'   => $group_setting->settings ?: new stdClass,
        ];
    }
}
