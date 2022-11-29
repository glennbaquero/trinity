<?php

namespace App\Models\ShippingMatrixes;

use App\Extendables\BaseModel as Model;

use App\Models\Areas\Area;

class ShippingMatrix extends Model
{
	/*
	|--------------------------------------------------------------------------
	| @Const
	|--------------------------------------------------------------------------
	*/


    /*
    |--------------------------------------------------------------------------
    | @Attributes
    |--------------------------------------------------------------------------
    */
   
    protected $appends = ['area_name'];

    public function getAreaNameAttribute()
    {
        if($this->area) {
            return $this->area->name;
        }
    }


	/*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'area' => $this->area->name,
        ];
    }

	public function area()
	{
		return $this->belongsTo(Area::class)->withTrashed();
	}

	/*
	|--------------------------------------------------------------------------
	| @Methods
	|--------------------------------------------------------------------------
	*/	


	/**
	 * Store/update specific resource from storage
	 * 
	 * @param  array $request
	 * @param  object $item
	 */
	public static function store($request, $item = null)
    {

        $vars = $request->except(['free', 'quantity', 'price']);

        $vars['free'] = isset($request->free) ? true: false;
        $vars['quantity'] = isset($request->quantity) ? true: false;
        $vars['price'] = isset($request->price) ? true: false;

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }
        
        return $item;
    }


	/*
	|--------------------------------------------------------------------------
	| @Renders
	|--------------------------------------------------------------------------
	*/


    /**
     * Render show url of specific resource in storage
     * 
     * @param  string $prefix 
     * @return string
     */
    public function renderShowUrl()
    {
        return route('admin.shipping-matrixes.show', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.shipping-matrixes.archive', $this->id);
    }

    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.shipping-matrixes.restore', $this->id);
    }

    /**
     * Render free status
     *
     * @return string
     */
    public function renderFreeStatus()
    {
        if($this->free) {
            return 'Free';
        }
        return 'No';
    }

	/*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/
}
