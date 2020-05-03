<?php

use Faker\Generator as Faker;

$factory->define(App\Account::class, function (Faker $faker) {
    return [
        //'name' => $faker->name,
        'domain_id' => 5,
        'company_key'   => strtolower(\Illuminate\Support\Str::random(25)),
        'ip'            => $faker->ipv4,
        'settings'      => (new \App\Settings\AccountSettings)->getAccountDefaults(),
        'custom_fields' => (object)['custom1' => '1', 'custom2' => '2', 'custom3' => '3'],
        // 'address1' => $faker->secondaryAddress,
        // 'address2' => $faker->address,
        // 'city' => $faker->city,
        // 'state' => $faker->state,
        // 'postal_code' => $faker->postcode,
        // 'country_id' => 4,
        // 'phone' => $faker->phoneNumber,
        // 'email' => $faker->safeEmail,
        // 'logo' => 'https://www.invoiceninja.com/wp-content/themes/invoice-ninja/images/logo.png',
    ];
});
