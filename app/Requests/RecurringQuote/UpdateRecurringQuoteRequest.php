<?php

namespace App\Requests\RecurringQuote;

use App\Repositories\Base\BaseFormRequest;

class CreateRecurringQuoteRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invitations.*.client_contact_id' => 'distinct',
            'frequency'                       => 'required|integer',
            'start_date'                      => 'required',
            'end_date'                        => 'required',
            'customer_id'                     => 'required|exists:customers,id,account_id,' . auth()->user(
                )->account_user()->account_id,
        ];
    }

}
