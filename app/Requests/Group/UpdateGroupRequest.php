<?php

namespace App\Requests\Group;

use App\Repositories\Base\BaseFormRequest;

class UpdateGroupRequest extends BaseFormRequest
{

    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }
}
