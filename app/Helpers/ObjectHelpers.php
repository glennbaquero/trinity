<?php

namespace App\Helpers;

class ObjectHelpers
{
    public static function getShortClassName($object){
        $result = new \ReflectionClass($object);
        $result = $result->getShortName();
        return preg_replace('/(?<! )(?<!^)[A-Z]/',' $0', $result);
    }
}