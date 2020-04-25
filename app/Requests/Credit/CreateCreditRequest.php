<?php

namespace App\Requests\Credit;

use App\Repositories\Base\BaseFormRequest;
use App\Settings;

class CreateCreditRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'customer_id'                   => 'required|exists:customers,id,account_id,' . auth()->user()->account_user()->account_id,
            'date'                          => 'required',
            'due_date'                      => 'required',
            'discount_total'                => 'required',
            'sub_total'                     => 'required',
            'total'                         => 'required',
            'tax_total'                     => 'required',
            'line_items'                    => 'required|array',
            'line_items.*.description'      => 'max:255',
            'line_items.*.product_id'       => 'required',
            'line_items.*.quantity'         => 'required|integer',
            'line_items.*.unit_price'       => 'required',
            'line_items.*.unit_discount'    => 'required',
            'line_items.*.unit_tax'         => 'required'
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->all();
        $input['line_items'] = isset($input['line_items']) ? (new Settings)->saveLineItems($input['line_items']) : [];
        $input['line_items'] = json_decode(json_encode($input['line_items']), true);

        $this->replace($input);
    }
}
