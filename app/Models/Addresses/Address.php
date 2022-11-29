<?php

namespace App\Models\Addresses;

use App\Models\Users\User;
use App\Models\Cities\City;
use App\Models\Provinces\Province;
use App\Models\Regions\Region;

use App\Extendables\BaseModel as Model;

class Address extends Model
{

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function city()
	{
		return $this->belongsTo(City::class)->withTrashed();
	}
    
	public function user()
	{
		return $this->belongsTo(User::class)->withTrashed();
	}

	public function province()
	{
		return $this->belongsTo(Province::class)->withTrashed();
	}

	public function region()
	{
		return $this->belongsTo(Region::class)->withTrashed();
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/
	public function getUserAddresses()
	{
		$address = collect($this)->except(['user_id', 'region_id', 'province_id', 'city_id', 'default', 'created_at', 'updated_at', 'deleted_at']);

		if(count($address)) {

	 		$address['region'] = [
				'value' => $this->region->id,
				'text' => $this->region->name,
			];
			
			$address['province'] = [
				'region_id' => $this->province->region_id,
				'value' => $this->province->id,
				'text' => $this->province->name,
			];

			$address['city'] = [
				'province_id' => $this->city->province_id,
				'value' => $this->city->id,
				'text' => $this->city->name,
			];
			
			$address['defaultAddress'] = $this->default;

			return $address;

		}

		return $address;

	}

	/**
     * Render full address
     * 
     * @return String
     */
    public function renderFullAddress()
    {
        $address = "{$this->street}, {$this->unit}, {$this->region->name}, {$this->province->name}, {$this->city->name}, {$this->zip}";

        return $address; 
     }

}
