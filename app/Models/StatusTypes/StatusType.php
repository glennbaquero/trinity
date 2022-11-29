<?php

namespace App\Models\StatusTypes;

use App\Extendables\BaseModel as Model;

class StatusType extends Model
{


    /*
    |--------------------------------------------------------------------------
    | @Const
    |--------------------------------------------------------------------------
    */
    const GO_NEXT_ACTION = 1;
    const COMPLETED = 2;
    const CANCELLED = 3; 



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


    /**
     * Store/Update specified resource from storage
     * 
     * @param  Array $request
     * @param  object $item
     */
    public static function store($request, $item = null)
    {

        $vars = $request->except([]);

        if(!$item) {
            $vars['order'] = StatusType::getOrder();
            $item = static::create($vars); 
        } else {
            $item->update($vars);
        }

        return $item;        

    }

    /**
     * Get order of newly stored status
     * 
     */
    public static function getOrder()
    {
        $status = StatusType::max('order');
        return $status + 1;
    }

    /**
     * Get action types
     * 
     * @return Array
     */
    public static function getActionTypes()
    {
        return collect([
            ['label' => 'Can go to next action', 'value' => StatusType::GO_NEXT_ACTION],
            ['label' => 'Complete', 'value' => StatusType::COMPLETED],
            ['label' => 'Cancel', 'value' => StatusType::CANCELLED]
        ]);
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
        return route('admin.status-types.show', $this->id);
    }

    /**
     * Render archive url of specific resource in storage
     * 
     * @return string
     */
    public function renderArchiveUrl()
    {
        return route('admin.status-types.archive', $this->id);
    }


    /**
     * Render restore url of specific resource in storage
     * 
     * @return string
     */
    public function renderRestoreUrl()
    {
        return route('admin.status-types.restore', $this->id);
    }


    /*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/

    public function checkOrderNumber()
    {
        $status = StatusType::where('order', $this->order)->first();

        if($status) {
            $order = StatusType::getOrder();
            $this->update(['order' => $order]);
        } 
        return $this;

    }

    public static function isActionCancel($id)
    {
        $status = StatusType::find($id);
        if($status->action_type === StatusType::CANCELLED) {
            return $status;
        }
        return false;
    }

    public static function isActionCompleted($id)
    {
        $status = StatusType::find($id);

        if($status->action_type === StatusType::CANCELLED || $status->action_type === StatusType::COMPLETED) {
            return true;
        }
        return false;

    }

}
