
<?php

namespace App\Transformations;

use App\Models\BankAccount;

trait BankAccountTransformable
{

    /**
     * @param BankAccount $bank_account
     * @return array
     */
    protected function transformBankAccount(BankAccount $bank_account)
    {
        return [
            'id'          => (int)$bank_account->id,
            'bank_id'          => (int)$bank_account->bank_id,
            'name'        => $bank_account->name,
            'username'    => $bank_account->username,
            'password'    => $bank_account->password
        ];
    }

}
