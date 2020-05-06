<?php

namespace App\Requests\Invoice;

use App\Settings\LineItemSettings;
use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
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

        return [
            'customer_id'    => 'required|exists:customers,id,account_id,' . auth()->user()->account_user()->account_id,
            'date'           => 'required',
            'due_date'       => 'required',
            'discount_total' => 'required',
            'sub_total'      => 'required',
            'total'          => 'required',
            'tax_total'      => 'required',
            'line_items'     => 'required|array',
        ];
    }

    protected function prepareForValidation()
    {
        $input = $this->all();
        $input['line_items'] = isset($input['line_items']) ? (new LineItemSettings)->save($input['line_items']) : [];

        $this->replace($input);
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
