<?php

namespace App\Models\Regions;

use App\Models\Provinces\Province;
use App\Modes\Users\MedicalRepresentative;
use App\Modes\Addresses\Address;

use App\Extendables\BaseModel as Model;

class Region extends Model
{

	protected $fillable = ['name'];

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function provinces()
	{
		return $this->hasMany(Province::class)->with('cities');
	}

	public function medicalRepresentative()
	{
		return $this->hasOne(MedicalRepresentative::class);
	}

	public function addresses()
	{
		return $this->hasMany(Address::class);
	}

}
