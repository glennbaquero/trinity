<?php

namespace App\Helpers;

use Faker\Factory as Faker;

class SeederHelpers
{
    public static function randomFile($dir = 'public/storage/tmp', $required = 0, $extension = 'jpg') {
        $path = null;

        if ($required) {
            $files = glob($dir . '/*.' . $extension);

            if (count($files) > $required) {
                $file = array_rand($files);
                $path = $files[$file];
            }
        }

        if ($path) {
            $path = str_replace('public/storage/', '', $path);
        } else {
            $faker = Faker::create();
            $path = $faker->image('public/storage/tmp', 400, 300, null, false);
            if ($path) {
                $path = 'tmp/' . $path;
            }
        }
        
        return $path;
    }
}