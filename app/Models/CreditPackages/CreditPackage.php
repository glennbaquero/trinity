<?php

namespace App\Models\CreditPackages;

use App\Extendables\BaseModel as Model;

class CreditPackage extends Model
{
    /*
	|--------------------------------------------------------------------------
	| @Consts
	|--------------------------------------------------------------------------
	*/

	const ENABLED = 1;
	const DISABLED = 0;



    /*
    |--------------------------------------------------------------------------
    | @Attributes
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | @Relationships
    |--------------------------------------------------------------------------
    */

    public function user() {
        return $this->belongsTo(Admin::class, 'user_id')->withTrashed();
    }

    /*
    |--------------------------------------------------------------------------
    | @Methods
    |--------------------------------------------------------------------------
    */

     /**
     * Format Object's Properties
     * @param  object
     * @return object
     */
    public static function formatItem($item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'type' => $item->type,
            'file_path' => $item->file_path,
            'extension' => $item->extension,
            'order_column' => $item->order_column,
            'path' => $item->renderFilePath(),
        ];
    }

    /**
     * Store/Update resource to storage
     *
     * @param  array $request
     * @param  object $item
     */
     public static function store($request, $item = null)
    {
        $vars = $request->only(['name','credits','price']);
        $vars['status'] = $request->status ? 1 : 0;

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
     * Render name
     *
     * @return String
     */
    public function renderName()
    {
        return $this->name;
    }

    /**
     * Render show url for specific item
     *
     * @return string/route
     */
    public function renderShowUrl($prefix = 'admin')
    {
        $route = $this->id;
        $name = 'credit-packages.show';

        return route($prefix . ".{$name}", $route);
    }

    /**
     * Render archive url for specific item
     *
     * @return string/route
    **/

    public function renderArchiveUrl($prefix = 'admin')
    {
        return route($prefix . '.credit-packages.archive', $this->id);
    }

     /**
     * Render archive url for specific item
     *
     * @return string/route
     */
    public function renderRestoreUrl($prefix = 'admin')
    {
        return route($prefix . '.credit-packages.restore', $this->id);
    }

    /*
    |--------------------------------------------------------------------------
    | @Checkers
    |--------------------------------------------------------------------------
    */
}
