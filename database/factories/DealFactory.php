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
use App\Models\Deal;
use App\Models\Customer;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Deal::class, function (Faker $faker) {
    $customer = factory(Customer::class)->create();
    $user = factory(User::class)->create();
    return [
        'account_id' => 1,
        'user_id' => $user->id,
        'title' => $faker->text,
        'description' => $faker->text,
        'is_completed' => 0,
        'customer_id' => $customer->id,
        'due_date' => $faker->dateTime(),
        'source_type' => 1,
        'task_status' => 1,
        'valued_at' => $faker->randomNumber(3)
    ];
});
