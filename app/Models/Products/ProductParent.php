<?php

namespace App\Models\Products;

use App\Extendables\BaseModel as Model;

use App\Traits\FileTrait;

use App\Models\Products\Product;
use App\Models\Specializations\Specialization;

class ProductParent extends Model
{
    use FileTrait;

	/*
	|--------------------------------------------------------------------------
	| @Const
	|--------------------------------------------------------------------------
	*/


	/*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/

    public function products() 
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class)->withTrashed();
    }

	/*
	|--------------------------------------------------------------------------
	| @Methods
	|--------------------------------------------------------------------------
	*/	

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

	/**
	 * Store/update specific resource from storage
	 * 
	 * @param  array $request
	 * @param  object $item
	 */
	public static function store($request, $item = null)
    {

        $vars = $request->except(['image_path']);
        $vars['image_path'] = static::storeImage($request, $item, 'image_path', 'product-parents');

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
        return route('admin.product-parents.show', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.product-parents.archive', $this->id);
    }

    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.product-parents.restore', $this->id);
    }


    public function renderSpecialization()
    {
        if($this->specialization) {
            return $this->specialization->name;
        }
        return 'N/A';
    }

	/*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/

}
