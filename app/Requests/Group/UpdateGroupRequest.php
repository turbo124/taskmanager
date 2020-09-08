<?php

namespace App\Requests\Group;

use App\Repositories\Base\BaseFormRequest;

class UpdateGroupRequest extends BaseFormRequest
{

    public function rules()
    {
        return [
            'name' => 'unique:groups,name,'.$this->id.',id,account_id,'.$this->account_id
        ];
    }
}
