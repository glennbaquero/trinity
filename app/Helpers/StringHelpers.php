<?php

namespace App\Helpers;

class StringHelpers {
	public static function truncate(string $string, int $end = 120, int $start = 0) {
		return substr($string, $start, $end);
	}

    public static function addRoutePrefix($string) {
        return $string = $string ? $string . '.' : null;
    }

	public static function slugify(string $string) {
        // replace non letter or digits by -
        $string = preg_replace('~[^\pL\d]+~u', '_', $string);

        // transliterate
        $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);

        // remove unwanted characters
        $string = preg_replace('~[^-\w]+~', '', $string);

        // trim
        $string = trim($string, '-');

        // remove duplicate -
        $string = preg_replace('~-+~', '-', $string);

        // lowercase
        $string = strtolower($string);

        if (empty($string)) {
            return 'n-a';
        }

        return $string;
    }
}