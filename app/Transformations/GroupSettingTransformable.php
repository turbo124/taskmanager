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
     * Transform the department
     *
     * @param Department $department
     * @return Department
     */
    protected function transformGroupSetting(GroupSetting $group_setting)
    {
        $prop = new GroupSetting;
        $prop->id = $group_setting->id;
        $prop->created_at = $group_setting->created_at;
        $prop->deleted_at = $group_setting->deleted_at;
        $prop->name = (string)$group_setting->name ?: '';
        $prop->settings = $group_setting->settings ?: new stdClass;

        return $prop;
    }
}
