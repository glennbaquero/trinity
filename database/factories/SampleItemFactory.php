<?php

use Faker\Generator as Faker;

use App\Helpers\SeederHelpers;
use Carbon\Carbon;

$factory->define(App\Models\Samples\SampleItem::class, function (Faker $faker) {
    return [
        'name' => $faker->word(3),
        'description' => $faker->paragraph,
        'image_path' => SeederHelpers::randomFile(),
        'date' => Carbon::now()->toDateTimeString(),
        'dates' => [Carbon::now()->toDateString(), Carbon::now()->addDays(2)->toDateString()],
    ];
});
