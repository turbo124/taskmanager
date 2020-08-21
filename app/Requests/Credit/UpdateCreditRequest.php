<?php

namespace App\Requests\Credit;

use App\Repositories\Base\BaseFormRequest;
use App\Settings\LineItemSettings;

class UpdateCreditRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id'    => 'required',
            'date'           => 'required',
            'due_date'       => 'required',
            'discount_total' => 'required',
            'sub_total'      => 'required',
            'total'          => 'required',
            'tax_total'      => 'required',
            'line_items'     => 'required|array',
            'number'         => 'nullable|unique:credits,number,' . $this->credit_id . ',id,account_id,' . $this->account_id,
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->all();
        $input['line_items'] = isset($input['line_items']) ? (new LineItemSettings)->save($input['line_items']) : [];

        $this->replace($input);
    }
}
