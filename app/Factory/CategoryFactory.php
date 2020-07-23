<?php


namespace App\Factory;


use App\Models\Account;
use App\Models\Category;
use App\Models\User;

class CategoryFactory
{
    /**
     * @param \App\Models\Account $account
     * @param \App\Models\User $user
     * @return \App\Models\Category
     */
    public static function create(Account $account, User $user): Category
    {
        $category = new Category;
        $category->account_id = $account->id;
        $category->user_id = $user->id;
        $category->status = 1;

        return $category;
    }
}