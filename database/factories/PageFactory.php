<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Pages\Page::class, function (Faker $faker) {
    return [
        'name' => $faker->word(2),
        'slug' => $faker->unique()->word(),
    ];
});
