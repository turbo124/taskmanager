<?php

namespace App\Factory;

use App\Product;

class ProductFactory
{
    public static function create(int $user_id, int $account_id): Product
    {
        $product = new Product;
        $product->user_id = $user_id;
        $product->account_id = $account_id;
        $product->quantity = 1;
     
        return $product;
    }
}
