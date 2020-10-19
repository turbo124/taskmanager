<?php

namespace App\Requests\Project;

use App\Repositories\Base\BaseFormRequest;
use Illuminate\Validation\Rule;

class CreateProjectRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'string|required',
            'description' => 'string|required',
            'customer_id' => 'numeric|required',
            'number'                => [
                Rule::unique('projects', 'number')->where(
                    function ($query) {
                        return $query->where('customer_id', $this->customer_id)->where('account_id', $this->account_id);
                    }
                )
            ],
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required'       => 'Title is required!',
            'description.required' => 'Description is required!',
            'customer_id.required' => 'Customer is required!'
        ];
    }

}
