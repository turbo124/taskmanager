<?php

namespace App\Requests\Cases;

use App\Models\Cases;
use App\Repositories\Base\BaseFormRequest;

class CreateCaseRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create', Cases::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'required',
            'message' => 'required',
        ];
    }
}
