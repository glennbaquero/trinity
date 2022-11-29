<?php

namespace App\Models\Cities;

use App\Models\Addresses\Address;
use App\Models\Provinces\Province;
use App\Models\ShippingMethod\Express;

use App\Extendables\BaseModel as Model;

use App\Helpers\StringHelpers;
use Laravel\Scout\Searchable;

class City extends Model
{

    use Searchable;

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function addresses()
	{
		return $this->hasMany(Address::class);
	}
    
	public function province()
	{
		return $this->belongsTo(Province::class)->withTrashed();
	}

	public function express()
	{
		return $this->hasOne(Express::class);
	}

	/**
	 * GETTERS
	 */
	
	public static function store($request, $item = null, $columns = ['province_id', 'name'])
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

    /**
     * RENDER
     */
    
    /**
     * Render show url of specific resource in storage
     * 
     * @param  string $prefix 
     * @return string
     */
    public function renderShowUrl($prefix = 'admin')
    {
        return route(StringHelpers::addRoutePrefix($prefix) . 'cities.show', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.cities.archive', $this->id);
    }

    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.cities.restore', $this->id);
    }

    public function renderShortTitle() {
        return substr($this->name, 0, 7) . '...';
    }

}
