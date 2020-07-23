<?php

use Faker\Generator as Faker;

$factory->define(
    \App\Models\Design::class, function (Faker $faker) {
    $customer = factory(\App\Models\Customer::class)->create();
    $user = factory(\App\Models\User::class)->create();

    return [
        'account_id' => 1,
        'user_id' => $user->id,
        'is_deleted' => false,
        'name' => $this->faker->firstName,
        'design' => '<HTML></HTML'
    ];
});