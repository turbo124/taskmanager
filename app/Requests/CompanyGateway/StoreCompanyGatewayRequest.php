<?php

namespace App\Requests\CompanyGateway;

use App\Repositories\Base\BaseFormRequest;

class StoreCompanyGatewayRequest extends BaseFormRequest
{

    public function rules()
    {
        $rules = [
            'gateway_key' => 'required',
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        $input = $this->all();

        if (isset($input['config'])) {
            $input['config'] = json_decode($input['config']);
        }

        if (isset($input['fees_and_limits'])) {
            $input['fees_and_limits'] = json_decode($input['fees_and_limits']);
        }

        $this->replace($input);
    }
}
