<?php

namespace App\Requests\Payment;

use App\Repositories\Base\BaseFormRequest;
use App\Rules\PaymentAppliedValidAmount;
use App\Rules\ValidCreditsPresentRule;

class UpdatePaymentRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoices' => ['required', 'array', 'min:1', new PaymentAppliedValidAmount, new ValidCreditsPresentRule],
        ];
    }
}
