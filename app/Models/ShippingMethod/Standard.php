<?php

namespace App\Models\ShippingMethod;

use App\Models\Provinces\Province;

use App\Extendables\BaseModel as Model;
use App\Helpers\StringHelpers;
use App\Traits\FileTrait;
use App\Helpers\FileHelpers;
use Laravel\Scout\Searchable;

class Standard extends Model
{

	use Searchable;
    
    protected static $logAttributes = ['fee', 'province_id'];

    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function province()
	{
		return $this->belongsTo(Province::class)->withTrashed();
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
			'province' => $this->province->name,
			'fee' => $this->fee
		];
	}


	/**
	 * @Getters
	 */
	public static function store($request, $item = null, $columns = ['fee', 'province_id'])
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

    public function hasProvince($province) {
        return $this->contains($province);
    }


	public function renderArchiveUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'shipping-standards.archive', $this->id);
	}

	public function renderRestoreUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'shipping-standards.restore', $this->id);
	}

	public function renderShowUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'shipping-standards.show', $this->id);
	}

}
