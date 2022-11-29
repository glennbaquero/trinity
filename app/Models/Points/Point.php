<?php

namespace App\Models\Points;

use App\Extendables\BaseModel as Model;

use App\Models\Users\Doctor;
use App\Models\Users\User;

use Laravel\Scout\Searchable;

class Point extends Model
{

	use Searchable;

	protected $guarded = [];
	
    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function pointable()
	{
		return $this->morphTo();
	}

	public function getUser($pointable_type,$id) {
		if(strpos($pointable_type,'Doctor')){
			$user = Doctor::find($id);
		} else if(strpos($pointable_type,'User')) {
			$user = User::find($id);
		}
		return $user;
	}

	public function getPoints($pointable_type,$id) {
		if(strpos($pointable_type,'Doctor')){
			$user = Doctor::find($id);
		} else if(strpos($pointable_type,'User')) {
			$user = User::find($id);
		}
		$format = number_format($user->points->sum('points'), 2);
		return $format;
	}

	public function getType($pointable_type) {
		if(strpos($pointable_type,'Doctor')){
			$type = 'Doctor';
		} else if(strpos($pointable_type,'User')) {
			$type = 'User';
		}
		return $type;
	}

	/*
	|--------------------------------------------------------------------------
	| Methods
	|--------------------------------------------------------------------------
	*/
	public function toSearchableArray()
	{
		return [
			'id' => $this->id,
			'user_first_name' => $this->pointable->first_name,
			'user_last_name' => $this->pointable->last_name
		];
	}

}
