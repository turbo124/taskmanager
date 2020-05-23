
<?php

namespace App\Requests\PaymentTerms;

use App\Repositories\Base\BaseFormRequest;

class StorePaymentTermsRequest extends BaseFormRequest
{

    public function rules()
    {
        $rules['name'] = 'required';
        return $rules;
    }
}
