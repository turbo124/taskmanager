<?php

namespace App\Requests\Payment;

use App\Repositories\Base\BaseFormRequest;
use App\Rules\Payment\CreditPaymentValidation;
use App\Rules\Payment\InvoicePaymentValidation;
use App\Rules\Payment\ValidAmount;

class CreatePaymentRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'amount'                => 'numeric|required',
            'amount'                => new ValidAmount($this->all()),
            'date'                  => 'required',
            'customer_id'           => 'bail|required|exists:customers,id',
            'invoices.*.invoice_id' => 'required|distinct|exists:invoices,id',
            'invoices.*.amount'     => 'required',
            'credits.*.credit_id'   => 'required|exists:credits,id',
            'credits.*.amount'      => 'required',
            'invoices'              => new InvoicePaymentValidation($this->all()),
            'credits'               => new CreditPaymentValidation($this->all()),
            'number'                => 'nullable',
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        $input = $this->all();

        $invoices = [];

        if (!empty($input['invoices'])) {
            foreach ($input['invoices'] as $key => $invoice) {
                if (empty($invoice['invoice_id'])) {
                    continue;
                }

                $invoices[] = $invoice;
            }

            $input['invoices'] = $invoices;
        }

        $input['is_manual'] = true;

        $this->replace($input);
    }
}
