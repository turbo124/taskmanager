<?php

namespace App\Requests\Cases;

use App\Repositories\Base\BaseFormRequest;
use App\Settings;

class CreateCaseRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'required',
            'message'   => 'required',
        ];
    }
}
