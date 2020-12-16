<?php

namespace App\Requests\TaxRate;

use App\Models\CompanyGateway;
use App\Models\TaxRate;
use App\Repositories\Base\BaseFormRequest;

class UpdateTaxRateRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $tax_rate = TaxRate::find($this->taxRate_id);
        return auth()->user()->can('update', $tax_rate);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'rate' => ['required']
        ];
    }
}
