<?php

namespace App\Factory;

use App\Product;
use App\Account;
use App\User;
use App\Customer;

class ProductFactory
{
    public static function create(User $user, Account $account): Product
    {
        $product = new Product;
        $product->user_id = $user->id;
        $product->account_id = $account->id;
        $product->quantity = 1;
     
        return $product;
    }
}
