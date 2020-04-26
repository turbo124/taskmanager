<?php

namespace App\Factory;

use App\Expense;
use Illuminate\Support\Facades\Log;

class ExpenseFactory
{
    /**
     * @param int $account_id
     * @param int $user_id
     * @return Expense
     */
    public static function create(int $account_id, int $user_id): Expense
    {
        $expense = new Expense();
        $expense->user_id = $user_id;
        $expense->account_id = $account_id;
        $expense->is_deleted = false;

        return $expense;
    }
}
