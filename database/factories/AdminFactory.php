<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

use App\Helpers\SeederHelpers;

$factory->define(App\Models\Users\Admin::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'image_path' => SeederHelpers::randomFile(),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
