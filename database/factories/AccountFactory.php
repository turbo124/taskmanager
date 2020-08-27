<?php

use Faker\Generator as Faker;

$factory->define(
    \App\Models\Account::class,
    function (Faker $faker) {
        $domain = \App\Models\Domain::first();
        $settings = (new \App\Settings\AccountSettings)->getAccountDefaults();
        $settings->phone = $faker->phoneNumber;
        $settings->website = $faker->url;
        $settings->address1 = $faker->address;
        $settings->city = $faker->city;
        $settings->email = $faker->email;
        $settings->inclusive_taxes = false;

        return [
            //'name' => $faker->name,
            'domain_id'     => $domain->id,
            'subdomain'     => 'loans-website.develop',
            'support_email' => $faker->email,
            'ip'            => $faker->ipv4,
            'settings'      => $settings,
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
    }
);
