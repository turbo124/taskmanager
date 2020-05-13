<?php


namespace App\Factory;


use App\Account;
use App\Category;

class CategoryFactory
{
    /**
     * @param Account $account_id
     * @return Category
     */
    public static function create(Account $account)
    {
        $category = new Category;
        $category->account_id = $account->id;
        $category->cover = '';
        $category->name = '';
        $category->slug = '';
        $category->description = '';
        $category->status = 1;

        return $category;
    }
}