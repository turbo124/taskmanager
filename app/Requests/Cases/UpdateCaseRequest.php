<?php

namespace App\Requests\Cases;

use App\Repositories\Base\BaseFormRequest;

class UpdateCaseRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status_id' => 'required',
            'subject'   => 'required',
            'message'   => 'required',
        ];
    }
}
