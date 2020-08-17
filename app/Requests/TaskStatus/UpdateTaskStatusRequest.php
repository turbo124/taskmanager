<?php

namespace App\Requests\TaskStatus;

use App\Repositories\Base\BaseFormRequest;

class UpdateTaskStatusRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required']
        ];
    }

}