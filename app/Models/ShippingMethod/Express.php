<?php

namespace App\Models\ShippingMethod;

use App\Models\Cities\City;

use App\Extendables\BaseModel as Model;
use App\Helpers\StringHelpers;
use App\Traits\FileTrait;
use App\Helpers\FileHelpers;
use Laravel\Scout\Searchable;

class Express extends Model
{

	use Searchable;
    
    protected static $logAttributes = ['fee', 'province_id'];

    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function city()
	{
		return $this->belongsTo(City::class)->withTrashed();
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
			'city' => $this->city->name,
			'fee' => $this->fee
		];
	}

	/**
	 * @Getters
	 */
	public static function store($request, $item = null, $columns = ['fee', 'city_id'])
    {

        $vars = $request->only($columns);
        if (!$item) {
            $item = static::create($vars);
            // foreach($users as $user) {
            //     $user->notify(new UserNotification($item, "New article {$item->title}", "An Admin created a new car {$item->article}"));
            // }
        } else {
            $item->update($vars);
        }
        
        return $item;
    }

	public function renderArchiveUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'shipping-expresses.archive', $this->id);
	}

	public function renderRestoreUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'shipping-expresses.restore', $this->id);
	}

	public function renderShowUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'shipping-expresses.show', $this->id);
	}
}
