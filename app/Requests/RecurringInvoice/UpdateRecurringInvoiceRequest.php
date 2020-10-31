<?php

namespace App\Requests\RecurringInvoice;

use App\Repositories\Base\BaseFormRequest;

class UpdateRecurringInvoiceRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invitations.*.contact_id' => 'distinct',
            'frequency'                => 'required',
            'start_date'               => 'required',
            'expiry_date'              => 'required',
            'customer_id'              => 'required|exists:customers,id,account_id,' . auth()->user()->account_user(
                )->account_id,
            'number'                   => 'nullable|unique:recurring_invoices,number,' . $this->id . ',id,account_id,' . $this->account_id,
        ];
    }

}
