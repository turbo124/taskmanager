<?php

use Faker\Generator as Faker;
use App\Models\User;
use App\Models\Task;
use App\Models\Customer;

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

use App\Models\Comment;

$factory->define(
    Comment::class,
    function (Faker $faker) {
        $user = factory(User::class)->create();

        return [
            'commentable_type' => '',
            'commentable_id'   => 0,
            'account_id'       => 1,
            'parent_id'        => 0,
            'is_active'        => 1,
            'user_id'          => $user->id,
            'parent_type'      => 1,
            'comment'          => $faker->text,
        ];
    }
);
