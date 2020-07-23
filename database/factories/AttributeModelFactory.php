<?php

use App\Models\Attribute;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Attribute::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->word
    ];
});