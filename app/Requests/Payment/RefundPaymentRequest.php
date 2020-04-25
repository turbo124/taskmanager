<?php

namespace App\Requests\Payment;

use App\Payment;
use App\Repositories\Base\BaseFormRequest;
use App\Rules\Refund\RefundValidation;
use App\Rules\Refund\InvoiceRefundValidation;


class RefundPaymentRequest extends BaseFormRequest
{
    protected function prepareForValidation()
    {
        $input = $this->all();
        if (!isset($input['gateway_refund'])) {
            $input['gateway_refund'] = false;
        }

        if (!isset($input['send_email'])) {
            $input['send_email'] = false;
        }

        $this->replace($input);
    }

    public function rules()
    {
        $input = $this->all();

        $rules = [
            'id'                    => 'bail|required',
            'id'                    => new RefundValidation($input),
            'amount'                => 'numeric',
            'date'                  => 'required',
            'invoices.*.invoice_id' => 'required',
            'invoices.*.amount'     => 'required',
            'invoices'              => new InvoiceRefundValidation($input),
        ];

        return $rules;
    }

    public function payment(): ?Payment
    {
        $input = $this->all();

        return Payment::whereId($input['id'])->first();
    }
}
