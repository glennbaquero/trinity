<?php

namespace App\Models\Calls;

use App\Models\Users\MedicalRepresentative;
use App\Extendables\BaseModel as Model;

use App\Helpers\StringHelpers;
use Laravel\Scout\Searchable;

class TargetCall extends Model
{

    use Searchable;
    
    /*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/
	public function medicalRepresentative()
	{
		return $this->belongsTo(\App\Models\Users\MedicalRepresentative::class)->withTrashed();
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
            'medical_representative' => $this->medicalRepresentative->name
        ];
    }

    /**
     * Generate months
     * 
     */
    public static function generateMonths()
    {

        return collect([
            ['name' => 'January', 'value' => 1],
            ['name' => 'February', 'value' => 2],
            ['name' => 'March', 'value' => 3],
            ['name' => 'April', 'value' => 4],
            ['name' => 'May', 'value' => 5],
            ['name' => 'June', 'value' => 6],
            ['name' => 'July', 'value' => 7],
            ['name' => 'August', 'value' => 8],
            ['name' => 'September', 'value' => 9],
            ['name' => 'October', 'value' => 10],
            ['name' => 'November', 'value' => 11],
            ['name' => 'December', 'value' => 12],                                  
        ]); 

    }


    /*
    |--------------------------------------------------------------------------
    | @Getters
    |--------------------------------------------------------------------------
    */
	
	public static function store($request, $item = null, $columns = ['medical_representative_id', 'month', 'year', 'target'])
    {

        $vars = $request->only($columns);

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
     * Render month name
     * 
     * @return array
     */
    public function renderMonth()
    {
        foreach (self::generateMonths() as $key => $value) {
            if($this->month === $value['value']) {
                return $value;
            }
        }
    }
    
    /**
     * Render show url of specific resource in storage
     * 
     * @param  string $prefix 
     * @return string
     */
    public function renderShowUrl($prefix = 'admin')
    {
        return route(StringHelpers::addRoutePrefix($prefix) . 'target-calls.show', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.target-calls.archive', $this->id);
    }

    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.target-calls.restore', $this->id);
    }

    /*
    |--------------------------------------------------------------------------
    | @Checkers
    |--------------------------------------------------------------------------
    */

    /**
     * Checker if target entry already exist
     * 
     * @param  array $request [description]
     * @param  int $id
     * @return Boolean
     */
    public static function alreadyExists($request, $itemID = null)
    {   

        $query = [
            'year' => $request->year,
            'month' => $request->month,
        ];

        $medRepID = $request->medical_representative_id;
        $target = TargetCall::where($query)->where('medical_representative_id', $medRepID);


        if($itemID && $target->first()) {
            return $target->first()->id === $itemID ? false: true;          
        }


        if($target->exists()) {
            return true;
        }
        return false;
    }    

}
