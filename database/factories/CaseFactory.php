<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(
    \App\Cases::class,
    function (Faker $faker) {
        $user = factory(\App\User::class)->create();
        $customer = factory(\App\Customer::class)->create();
        $account = \App\Account::first();
        return [
            'status_id'   => \App\Cases::STATUS_DRAFT,
            'subject'     => $faker->word,
            'message'     => $faker->sentence,
            'account_id'  => $account->id,
            'user_id'     => $user->id,
            'customer_id' => $customer->id
        ];
    }
);
