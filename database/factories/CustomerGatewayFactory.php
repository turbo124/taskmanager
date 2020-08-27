<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(
    \App\Models\CustomerGateway::class,
    function (Faker $faker) {
        $user = factory(\App\Models\User::class)->create();
        $customer = factory(\App\Models\Customer::class)->create();
        $account = \App\Models\Account::first();
        return [
            'account_id'    => $account->id,
            'customer_id'   => $customer->id,
        ];
    }
);
