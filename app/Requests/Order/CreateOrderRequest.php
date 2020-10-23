<?php

namespace App\Requests\Order;

use App\Rules\Order\OrderTotals;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $input = $this->all();

        return [
            'customer_id'    => 'required',
            'date'           => 'required',
            'due_date'       => 'required',
            'discount_total' => 'required',
            'sub_total'      => 'required',
            'total'          => 'required',
            'tax_total'      => 'required',
            'line_items'     => [
                'required',
                'array',
                new OrderTotals($input)
            ],
            //'number'         => 'nullable|unique:invoices,number,customer,' . $this->customer_id,
            'number'         => [
                Rule::unique('product_task', 'number')->where(
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
            'comment.required' => 'You must enter a comment!',
            'task_id.required' => 'There was an unexpected error!',
            'user_id.required' => 'There was an unexpected error!',
        ];
    }
}
