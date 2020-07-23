<?php

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
use App\Models\File;
use App\Models\Task;
use App\Models\User;

$factory->define(File::class, function (Faker $faker) {
    $user = factory(User::class)->create();
    $task = factory(Task::class)->create();

    return [
        'account_id' => 1,
        'company_id' => null,
        'is_active' => 1,
        'fileable_id' => $task->id,
        'fileable_type' => 'App\Models\Task',
        'user_id' => $user->id,
        'name' => $faker->text,
        'file_path' => $faker->word,
        'preview' => null,
        'type' => null,
        'size' => null,
        'width' => null,
        'height' => null,
        'is_default' => 0,
        'deleted_at' => null
    ];
});
