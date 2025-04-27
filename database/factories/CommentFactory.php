<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'snippet_id' => function () {
            return factory(App\Snippet::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'comment' => $faker->paragraph,
        'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
    ];
});