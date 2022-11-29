<?php

namespace App\Models\Pharmacies;

use App\Extendables\BaseModel as Model;
use App\Traits\FileTrait;

class Pharmacy extends Model
{
    use FileTrait;

    /**
     * Store/Update specified resource from storage
     * 
     * @param  Array $request
     * @param  object $item
     */
    public static function store($request, $item = null)
    {
        $vars = $request->all();
        $vars['image_path'] = static::storeImage($request, $item, 'image_path', 'pharmacies');

        if(!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;
    }

    /**
     * Renderers
     */
    
    /**
     * Render show url of specific resource in storage
     * 
     * @param  string $prefix 
     * @return string
     */
    public function renderShowUrl()
    {
        return route('admin.pharmacies.show', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.pharmacies.archive', $this->id);
    }

    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.pharmacies.restore', $this->id);
    }
}
