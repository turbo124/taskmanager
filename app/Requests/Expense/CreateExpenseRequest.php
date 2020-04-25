<?php

namespace App\Requests\Expense;

use App\Repositories\Base\BaseFormRequest;

class CreateExpenseRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'expense_date' => 'required',
            'invoice_category_id' => 'required',
            'amount' => 'required'
        ];

        return $rules;
    }

}
