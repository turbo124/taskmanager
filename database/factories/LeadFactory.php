<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(
    \App\Models\Lead::class,
    function (Faker $faker) {
        $user = factory(\App\Models\User::class)->create();
        return [
            'address_1'   => $faker->streetAddress,
            'address_2'   => null,
            'zip'         => $faker->postcode,
            'city'        => $faker->city,
            'source_type' => 1,
            'account_id'  => 1,
            'user_id'     => $user->id,
            'task_status' => 1,
            'name'        => $faker->word,
            'description' => $faker->sentence,
            'first_name'  => $faker->firstName,
            'last_name'   => $faker->lastName,
            'phone'       => $faker->phoneNumber,
            'email'       => $faker->safeEmail
        ];
    }
);
