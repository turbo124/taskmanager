<?php

namespace App\Requests\Lead;

use App\Repositories\Base\BaseFormRequest;

class CreateLeadRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'  => 'required|string',
            'last_name'   => 'required|strong',
            'email'       => 'required|unique:leads',
            'name'        => 'required',
            'start_date' => 'nullable',
            //'task_status' => 'required',

        ];
    }

}
