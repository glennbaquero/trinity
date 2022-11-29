<?php

namespace App\Helpers;

use Auth;

class AuthHelpers
{
	public static function getGuard($request) {
		$class = get_class($request->user());

		switch ($class) {
			case 'App\Models\Users\Admin':
				return 'admin';
			case 'App\Models\Users\Merchant':
				return 'merchant';
			case 'App\Models\Users\User':
				return 'web';
		}

		return false;
	}
}