<?php

namespace App\Requests\Promocode;

use App\Repositories\Base\BaseFormRequest;

class UpdatePromocode extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'scope'       => ['required'],
            'scope_value' => ['required'],
            'amount'      => ['required'],
            'quantity'    => ['required'],
            'description' => ['required'],
            'expiry_date' => ['required']
        ];
    }
}