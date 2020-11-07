
<?php

namespace App\Requests\BankAccount;


use App\Repositories\Base\BaseFormRequest;

class CreateBankAccountRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'unique:bank_accounts'],
            'username' => 'required',
            'password' => 'required'
        ];
    }
}
