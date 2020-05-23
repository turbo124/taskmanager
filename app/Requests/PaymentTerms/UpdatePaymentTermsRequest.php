
<?php

namespace App\Requests\PaymentTerms;

use App\Repositories\Base\BaseFormRequest;

class UpdatePaymentTermsRequest extends BaseFormRequest
{

    public function rules()
    {
        return [
            'name' => 'required',
            'number_of_days' => 'required',
        ];
    }
}
