<?php

namespace App\Models\Specializations;

use App\Extendables\BaseModel as Model;

use App\Models\Users\Doctor;
use App\Models\Products\Product;

use Laravel\Scout\Searchable;
use App\Traits\FileTrait;

class Specialization extends Model
{

    use Searchable, FileTrait;
    
    protected $appends = ['full_image'];

    /*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/
	public function products()
	{
		return $this->belongsToMany(Product::class, 'product_specializations', 'product_id', 'specialization_id');
	}

	public function doctors()
	{
		return $this->hasMany(Doctor::class);
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
            'name' => $this->name
        ];
    }


    /**
     * Store/Update specified resource from storage
     * 
     * @param  Array $request
     * @param  object $item
     */
    public static function store($request, $item = null)
    {

        $vars = $request->except(['image_path']);
        $vars['image_path'] = static::storeImage($request, $item, 'image_path', 'products');

        if(!$item) {
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
        return route('admin.specializations.show', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.specializations.archive', $this->id);
    }


    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.specializations.restore', $this->id);
    }

    /**
     * Render description of specified resource from storage
     * 
     * @return String
     */
    public function renderShortDescription()
    {
        if($this->description) {
            return str_limit(strip_tags($this->description), 100);
        }        
    }

    /*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/

    /*
    |--------------------------------------------------------------------------
    | @Appends
    |--------------------------------------------------------------------------
    */
   
    /**
     * Append image full path
     * 
     * @return string $image
     */
    public function getFullImageAttribute()
    {
        return url($this->renderImagePath());
    }

}
