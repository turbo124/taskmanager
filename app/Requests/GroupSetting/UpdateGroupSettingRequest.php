<?php

namespace App\Requests\GroupSetting;

use App\Repositories\Base\BaseFormRequest;

class UpdateGroupSettingRequest extends BaseFormRequest
{

    public function rules()
    {
        $rules['name'] = 'required';
        return $rules;
    }
}
