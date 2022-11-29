<?php

namespace App\Helpers;

use Auth;

class EnvHelpers
{
	public static function isDev() {
		return config('app.env') === 'local';
	}
}