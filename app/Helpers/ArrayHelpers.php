<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class ArrayHelpers
{
	public static function diff($newArray, $oldArray) {
		$newArray = array_map('strval', $newArray);
		$oldArray = array_map('strval', $oldArray);

        $addedValues = [];
        $removedValues = [];

        foreach ($newArray as $newId) {
            if (!in_array($newId, $oldArray)) {
                $addedValues[] = $newId;
            }
        }

        foreach ($oldArray as $prevId) {
            if (!in_array($prevId, $newArray)) {
                $removedValues[] = $prevId;
            }
        }

        return [
        	'attributes' => $addedValues,
        	'old' => $removedValues,
        	'action' => count($addedValues) || count($removedValues),
        ];
	}

    public static function getRandomFromArray($array){
        return $array[array_rand($array)];
    }

    public static function sortArray($array, $column) {
        return array_values(Arr::sort($array, function ($value) use ($column) {
            return $value[$column];
        }));
    }
}