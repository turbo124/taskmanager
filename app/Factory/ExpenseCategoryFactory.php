<?php


namespace App\Factory;


use App\Account;
use App\CaseCategory;
use App\Category;
use App\ExpenseCategory;
use App\User;

class ExpenseCategoryFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return ExpenseCategory
     */
    public static function create(Account $account, User $user)
    {
        $category = new ExpenseCategory();
        $category->account_id = $account->id;
        $category->user_id = $user->id;

        return $category;
    }
}