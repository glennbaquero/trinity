<?php

namespace App\Models\Provinces;

use App\Models\Cities\City;
use App\Models\Addresses\Address;
use App\Models\ShippingMethod\Standard;
use App\Models\Regions\Region;

use App\Helpers\StringHelpers;
use Laravel\Scout\Searchable;

use App\Extendables\BaseModel as Model;

class Province extends Model
{
	use Searchable;

	protected $fillable = ['region_id', 'name'];
    
    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function cities()
	{
		return $this->hasMany(City::class);
	}

	public function addresses()
	{
		return $this->hasMany(Address::class);
	}

	public function standard()
	{
		return $this->hasOne(Standard::class);
	}

	public function region()
	{
		return $this->belongsTo(Region::class)->withTrashed();
	}


	/*
	|--------------------------------------------------------------------------
	| Renders
	|--------------------------------------------------------------------------
	*/
	public function renderArchiveUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'provinces.archive', $this->id);
	}

	public function renderRestoreUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'provinces.restore', $this->id);
	}

	public function renderShowUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'provinces.show', $this->id);
	}


	/*
	|--------------------------------------------------------------------------
	| Methods
	|--------------------------------------------------------------------------
	*/
	public static function store($request, $item = null, $columns = ['region_id', 'name'])
    {
        $vars = $request->only($columns);

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        if ($request->hasFile('images')) {
            $item->addImages($request->file('images'));
        }

        return $item;
    }

}
