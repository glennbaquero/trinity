<?php

namespace App\Models\BankDetails;

use App\Extendables\BaseModel as Model;
use App\Helpers\StringHelpers;
use App\Traits\FileTrait;
use App\Helpers\FileHelpers;
use Laravel\Scout\Searchable;

class BankDetail extends Model
{

    use Searchable;

    protected static $logAttributes = ['name', 'account_number', 'branch'];

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'branch' => $this->branch
        ];
    }
    
    /**
	 * @Getters
	 */
	public static function store($request, $item = null, $columns = ['name', 'account_number', 'branch'])
    {

        $vars = $request->only($columns);
        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }
        
        return $item;
    }

    public function renderArchiveUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'bank-details.archive', $this->id);
    }

    public function renderRestoreUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'bank-details.restore', $this->id);
    }

    public function renderShowUrl($prefix = 'admin') {
        return route(StringHelpers::addRoutePrefix($prefix) . 'bank-details.show', $this->id);
    }

}
