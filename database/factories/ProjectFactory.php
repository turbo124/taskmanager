<?php

use App\Models\Project;
use Faker\Generator as Faker;

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

$factory->define(
    Project::class,
    function (Faker $faker) {
        $user = factory(\App\Models\User::class)->create();
        return [
            'account_id'     => 1,
            'assigned_to'    => null,
            'user_id'        => $user->id,
            'customer_id'    => null,
            'name'           => $faker->text,
            'description'    => $faker->text,
            'is_completed'   => 0,
            'private_notes'  => null,
            'budgeted_hours' => null,
            'task_rate'      => null,
            'due_date'       => null,
            'deleted_at'     => null,
            'is_deleted'     => 0
        ];
    }
);