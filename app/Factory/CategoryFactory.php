<?php


namespace App\Factory;


use App\Account;
use App\Category;
use App\User;

class CategoryFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return Category
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