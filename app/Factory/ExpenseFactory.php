<?php

namespace App\Factory;

use App\Expense;
use App\Account;
use App\User;
use App\Customer;
use Illuminate\Support\Facades\Log;

class ExpenseFactory
{
    /**
     * @param int $account_id
     * @param int $user_id
     * @return Expense
     */
    public static function create(Account $account, User $user): Expense
    {
        $expense = new Expense();
        $expense->user_id = $user->id;
        $expense->account_id = $account->id;
        $expense->is_deleted = false;

        return $expense;
    }
}
