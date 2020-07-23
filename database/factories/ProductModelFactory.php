<?php

/*
  |--------------------------------------------------------------------------
  | Model Factories
  |--------------------------------------------------------------------------
  |
  | Here you may define all of your model factories. Model factories give
  | you a convenient way to create models for testing and seeding your
  | database. Just tell the factory how a default model should look.
  |
 */

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use App\Models\User;

$factory->define(
    Product::class,
    function (Faker\Generator $faker) {
        $product = $faker->unique()->sentence;
        $user = factory(User::class)->create();
        $company = factory(\App\Models\Company::class)->create();

        return [
            'company_id'    => $company->id,
            'account_id'    => 1,
            'user_id'       => $user->id,
            'quantity'      => 0,
            'sku'           => $faker->numberBetween(1111111, 999999),
            'name'          => $product,
            'slug'          => \Illuminate\Support\Str::slug($product),
            'description'   => $faker->paragraph,
            'price'         => $faker->randomNumber(2),
            'status'        => 1,
            'length'        => 5,
            'width'         => 10,
            'height'        => 15,
            'weight'        => 20,
            'mass_unit'     => 'gms',
            'distance_unit' => 'cm'
        ];
    }
);
