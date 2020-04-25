<?php

namespace App\Requests\CompanyGateway;

use App\Repositories\Base\BaseFormRequest;

class UpdateCompanyGatewayRequest extends BaseFormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $rules = [
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
