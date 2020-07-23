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

use App\Models\Category;
use Illuminate\Http\UploadedFile;

$factory->define(
    Category::class,
    function (Faker\Generator $faker) {
        $user = factory(\App\Models\User::class)->create();
        $name = $faker->unique()->randomElement(
            [
                'Gear',
                'Clothing',
                'Shoes',
                'Diapering',
                'Feeding',
                'Bath',
                'Toys',
                'Nursery',
                'Household',
                'Grocery'
            ]
        );
        //$file = UploadedFile::fake()->image('category.png', 600, 600);
        return [
            'user_id'     => $user->id,
            'account_id'  => 1,
            'name'        => $name,
            'slug'        => \Illuminate\Support\Str::slug($name),
            'description' => $faker->paragraph,
            //'cover' => $file->store('categories', ['disk' => 'public']),
            'status'      => 1
        ];
    }
);
