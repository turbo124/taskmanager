<?php

namespace App\Requests\Payment;

use App\Repositories\Base\BaseFormRequest;
use App\Rules\Payment\CreditPaymentValidation;
use App\Rules\Payment\InvoicePaymentValidation;
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
            'invoices' => [
                'array',
                'min:1',
                new InvoicePaymentValidation($this->all()),
                new CreditPaymentValidation($this->all())
            ],
            'number'   => 'nullable|unique:payments,number,' . $this->payment_id . ',id,account_id,' . $this->account_id,
        ];
    }
}
