<?php

use Faker\Generator as Faker;

use App\Helpers\ArrayHelpers;
use App\Helpers\SeederHelpers;
use App\Models\Pages\PageItem;

$factory->define(PageItem::class, function (Faker $faker) {
	$type = ArrayHelpers::getRandomFromArray(PageItem::getTypes());

	switch ($type['value']) {
		case PageItem::TYPE_IMAGE:
				$content = SeederHelpers::randomFile();
			break;
		
		default:
				$content = $faker->word(3);
			break;
	}

    return [
        'name' => $faker->word(3),
        'slug' => $faker->unique()->word(),
        'content' => $content,
        'type' => $type['value'],
    ];
});
