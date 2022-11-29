<?php

namespace App\Models\Notifications;

use App\Extendables\BaseModel as Model;

class DeviceToken extends Model
{
    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function user()
	{
		return $this->morphTo();
	}
}
