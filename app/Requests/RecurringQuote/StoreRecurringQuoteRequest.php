<?php

namespace App\Requests\RecurringQuote;

use App\Repositories\Base\BaseFormRequest;

class StoreRecurringQuoteRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id,account_id,' . auth()->user()->account_user()->account_id,
        ];
    }

}
