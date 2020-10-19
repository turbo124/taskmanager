<?php

namespace App\Requests\RecurringQuote;

use App\Repositories\Base\BaseFormRequest;
use Illuminate\Validation\Rule;

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
            'invitations.*.contact_id' => 'distinct',
            'frequency'                => 'required|integer',
            'start_date'               => 'required',
            'expiry_date'              => 'required',
            'customer_id'              => 'required|exists:customers,id,account_id,' . auth()->user()->account_user(
                )->account_id,
            'number'         => [
                Rule::unique('recurring_quotes', 'number')->where(
                    function ($query) {
                        return $query->where('customer_id', $this->customer_id)->where('account_id', $this->account_id);
                    }
                )
            ],
        ];
    }

}
