<?php

namespace App\Helpers;

class NumberHelpers {
	public static function generateMobileNumber() {
        return '+639' . (string) rand(100, 999) . (string) rand(1000, 9999);
    }

    public static function toMoney($value, $prefix = '₱') {
    	$prefix = $prefix ? $prefix . ' ' : null;
    	return $prefix . number_format($value, 2, '.', ',');
    }

    public static function computeDiscount($value, $discount, $type = null) {
    	$result = ($value * $discount) / 100;
        
    	switch (strtolower($type)) {
    		case 'add':
    				$result = $value + $result;
    			break;
    		
    		case 'subtract':
    				$result = $value - $result;
    			break;
    	}

    	return $result;
    }
}