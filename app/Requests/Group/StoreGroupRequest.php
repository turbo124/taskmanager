<?php

namespace App\Requests\Group;

use App\Repositories\Base\BaseFormRequest;

class StoreGroupRequest extends BaseFormRequest
{

    public function rules()
    {
        return [
             'name' => 'required|unique:groups,name,null,null,account_id,' . auth()->user()->account_user()->account_id
        ];
    }
}
