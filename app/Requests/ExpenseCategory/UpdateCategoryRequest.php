<?php

namespace App\Requests\ExpenseCategory;

use App\Repositories\Base\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('expense_categories')->ignore(request()->segment(3))]
        ];
    }

}
