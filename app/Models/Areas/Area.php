<?php

namespace App\Models\Areas;

use App\Extendables\BaseModel as Model;

class Area extends Model
{
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

        $vars = $request->except([]);

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
        return route('admin.areas.show', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.areas.archive', $this->id);
    }

    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.areas.restore', $this->id);
    }


	/*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/
}
