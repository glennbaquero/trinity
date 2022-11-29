<?php

namespace App\Models\Payouts;

use App\Extendables\BaseModel as Model;

use App\Services\PushServiceDoctor;
use App\Notifications\Admin\Payouts\PayoutNotification;

use App\Models\Users\Doctor;
use App\Models\Users\Admin;
use App\Traits\CreditTrait;

use Auth;
use Carbon\Carbon;

class Payout extends Model
{
	use CreditTrait;
	/*
	|--------------------------------------------------------------------------
	| @Consts
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray()
	{
	    $searchable = [
	        'id' => $this->id,
	        'fields' => $this->fields,
	    ];
	    
	    return $searchable;
	}
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

	public function doctor()
	{
		return $this->belongsTo(Doctor::class)->withTrashed();
	}

	/*
	|--------------------------------------------------------------------------
	| @Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Store/Update resource to storage
	 * 
	 * @param  array $request
	 * @param  object $item
	 */
	 public static function store($request, $item = null)
	{
	    $vars = $request->only(['reason']);

	    $item->update($vars);
	    

	    return $item;
	}

    /**
     * Update payout status
     * 
     * @param  boolean $status
     */
    public function updateStatus($status) 
    {
        $admin = Auth::guard('admin')->user();
        $subject = '';
        $value_approved = $this->value;
        if($status) {

            $subject = 'Payout Request Approved';
            $message = 'Your payout request worth ' . $value_approved . ' has been Approved';
            
            $this->update([
                'approved_by' => $admin->id
            ]);

        } else {

            $subject = 'Payout Request Declined';
            $message = 'Your payout request worth ' . $value_approved . ' has been Declined';
            
            $this->update([
                'declined_by' => $admin->id
            ]);

        }

        $this->doctor->notify(new PayoutNotification($subject, $message));   
        $this->sendPushNotification($subject, $message);
    
    }

    /**
     * Send push notification for payout status update
     * 
     */
    public function sendPushNotification($title, $message)
    {
        $service = new PushServiceDoctor($title, $message);
        $service->pushToOne($this->doctor);        
    }

	/*
	|--------------------------------------------------------------------------
	| @Renders
	|--------------------------------------------------------------------------
	*/

	/**
     * Render show url for specific item
     * 
     * @return string/route
     */
	public function renderDisapprovalFormUrl($prefix = 'admin') 
    {
        $route = $this->id;
        $name = 'payouts.disapproval-form';

        return route($prefix . ".{$name}", $route);
    }


     /**
     * Render name for specific item
     * 
     * @return string/route
     */
    public function renderName() 
    {
        return $this->name;
    }

    /**
     * Render archive url for specific item
     * 
     * @return string/route
     */
    public function renderArchiveUrl($prefix = 'admin') 
    {
        return route($prefix . '.payouts.archive', $this->id);
    }

    /**
     * Render archive url for specific item
     * 
     * @return string/route
     */
    public function renderRestoreUrl($prefix = 'admin') 
    {
        return route($prefix . '.payouts.restore', $this->id);
    }

    public function renderApproveUrl($prefix = 'admin') {
        return route($prefix . '.payouts.approve', $this->id);
    }

    public function renderDisapproveUrl($prefix = 'admin') {
        return route($prefix . '.payouts.disapprove', $this->id);
    }

    /*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/

    /**
     * Check pending payout request using doctor_id
     * 
     * @param  int $doctorID
     */
    public static function checkPending($doctorID) 
    {
        $payout = Payout::where('doctor_id', $doctorID)
                ->where(['approved_by' => null, 'declined_by' => null])->exists();

        return $payout;
    }


    /**
     * Check if item can be Archived
     * 
     */
    public function canArchive()
    {
        if($this->approved_by || $this->declined_by) {
            return true;
        }    
    }


	/*
	|--------------------------------------------------------------------------
	| @Getters
	|--------------------------------------------------------------------------
	*/

}
