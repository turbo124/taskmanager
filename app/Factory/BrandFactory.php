<?php


namespace App\Factory;


use App\Account;
use App\Brand;
use App\Category;
use App\User;

class BrandFactory
{
    /**
     * @param Account $account
     * @param User $user
     * @return Brand
     */
    public static function create(Account $account, User $user): Brand
    {
        $brand = new Brand;
        $brand->account_id = $account->id;
        $brand->user_id = $user->id;
        $brand->status = 1;

        return $brand;
    }
}