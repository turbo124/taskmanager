<?php

namespace App\Requests\GroupSetting;

use App\Repositories\Base\BaseFormRequest;

class StoreGroupSettingRequest extends BaseFormRequest
{

    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }
}
