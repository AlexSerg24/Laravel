<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'post_name'->$this->faker->numberBetween(1, 3),
        'auto_category'->$this->faker->numberBetween(1, 6),
    ];
});
