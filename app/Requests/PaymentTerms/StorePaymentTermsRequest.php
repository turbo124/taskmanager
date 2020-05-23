
<?php

namespace App\Requests\PaymentTerms;

use App\Repositories\Base\BaseFormRequest;

class StorePaymentTermsRequest extends BaseFormRequest
{

    public function rules()
    {
        $rules['name'] = 'required';
        $rules['number_of_days'] = 'required';
        return $rules;
    }
}
