<?php

namespace App\Requests\GroupSetting;

use App\Repositories\Base\BaseFormRequest;

class StoreGroupSettingRequest extends BaseFormRequest
{

    public function rules()
    {
        $rules['name'] = 'required';
        return $rules;
    }

    protected function prepareForValidation()
    {
        $input = $this->all();


        $this->replace($input);
    }

    public function messages()
    {
        return [
            'settings' => 'settings must be a valid json structure'
        ];
    }
}
