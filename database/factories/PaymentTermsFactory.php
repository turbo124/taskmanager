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

use App\Models\Department;
use App\Models\User;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(
    \App\Models\PaymentTerms::class, function (Faker\Generator $faker) {
    $user = factory(User::class)->create();
    return [
        'account_id' => 1,
        'name' => $faker->unique()->word,
        'user_id' => $user->id
    ];
});
