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
use App\Models\Customer;
use App\Models\Company;
use App\Models\Account;
use App\Models\User;

$factory->define(Customer::class, function (Faker\Generator $faker) {

    $company = factory(Company::class)->create();
    $user = factory(User::class)->create();
    //$account = factory(Account::class)->create();

    return [
        'name' => $faker->name(),
        'website' => $faker->url,
        'currency_id' => 2,
        'private_notes' => $faker->text(200),
        'balance' => 2020.22,
        'paid_to_date' => 0,
        'custom_value1' => $faker->text(20),
        'custom_value2' => $faker->text(20),
        'custom_value3' => $faker->text(20),
        'custom_value4' => $faker->text(20),
        'settings' => (new \App\Settings\CustomerSettings())->getCustomerDefaults(),
        'account_id' => 1,
        'user_id' => $user->id,
        'phone' => $faker->phoneNumber,
        'status' => 1

    ];
});
