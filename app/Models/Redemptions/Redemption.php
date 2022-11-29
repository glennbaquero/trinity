<?php

namespace App\Models\Redemptions;

use App\Models\Users\User;
use App\Models\Users\Doctor;

use App\Extendables\BaseModel as Model;

class Redemption extends Model
{
	protected $guarded = [];

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function redemptionable()
	{
		return $this->belongsTo(User::class)->withTrashed();
	}
}
