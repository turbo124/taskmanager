<?php

namespace App\Repositories;

use App\GroupSetting;
use App\Repositories\Base\BaseRepository;

class GroupSettingRepository extends BaseRepository
{
    /**
     * GroupSettingRepository constructor.
     * @param GroupSetting $group_setting
     */
    public function __construct(GroupSetting $group_setting)
    {
        parent::__construct($group_setting);
    }

    /**
     * Gets the class name.
     *
     * @return     string  The class name.
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param int $id
     * @return GroupSetting
     */
    public function findGroupSettingById(int $id): GroupSetting
    {
        return $this->findOneOrFail($id);
    }

    public function save($data, GroupSetting $group_setting): ?GroupSetting
    {
        $group_setting->fill($data);
        $group_setting->save();

        return $group_setting;
    }
}
