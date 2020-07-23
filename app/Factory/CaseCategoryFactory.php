<?php


namespace App\Factory;


use App\Models\Account;
use App\Models\CaseCategory;
use App\Models\Category;
use App\Models\User;

class CaseCategoryFactory
{
    /**
     * @param \App\Models\Account $account
     * @param \App\Models\User $user
     * @return CaseCategory
     */
    public static function create(Account $account, User $user)
    {
        $category = new CaseCategory;
        $category->account_id = $account->id;
        $category->user_id = $user->id;

        return $category;
    }
}