<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(3),
        'description' => $faker->sentence(4),
        'status' => $faker->randomElement(['view' ,'in_progress', 'done']),
        'user_id' => $faker->numberBetween(1, 20)
    ];
});
