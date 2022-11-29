<?php

namespace App\Models\Refunds;

use App\Extendables\BaseModel as Model;

use App\Models\Users\User;
use App\Models\Users\Doctor;
use App\Models\Schedules\Schedule;
use App\Models\Consultations\Consultation;

use App\Notifications\Admin\Refunds\RefundNotification;

use App\Traits\CreditTrait;

use Auth;
use Carbon\Carbon;

class Refund extends Model
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

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function doctor()
	{
		return $this->belongsTo(Doctor::class);
	}

	public function schedule()
	{
		return $this->belongsTo(Schedule::class);
	}

	public function consultation()
	{
		return $this->belongsTo(Consultation::class);
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
	 public static function store($request)
	{
	    $vars = [];
	    $consultation = Consultation::where('id', $request->consultation_id)->first();
	    if($consultation) {
	    	$vars =  [
    			'user_id' => $consultation->user_id, 
    			'doctor_id' => $consultation->doctor_id,
    			'schedule_id' => $consultation->schedule_id,
	    	];
	    	$vars['consultation_id'] = $request->consultation_id;
	    	$vars['reason'] = $request->reason;
		    self::create($vars);
		    $consultation->update(['status' => Consultation::REFUNDED]);
	    }
	}

    public function updateStatus($status, $consultation_id, $reason = null) 
    {
        $admin = Auth::guard('admin')->user();
        $subject = '';

        if($status) {

            $subject = 'Refund Request Approved';

            $this->update([
                'approved_by' => $admin->id,
                'approved_at' => Carbon::now(),
            ]);
            $this->user->notify(new RefundNotification($subject, 'Approved', $this->consultation));

        } else {

            $subject = 'Refund Request Declined';

            $this->update([
                'declined_by' => $admin->id,
                'declined_at' => Carbon::now(),
                'disapproved_reason' => $reason                
            ]);

           $this->user->notify(new RefundNotification($subject, 'Declined', $this->consultation, $reason));            
        }

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
        $name = 'refunds.disapproval-form';

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
     * Render show url for specific item
     *
     * @return string/route
     */
    public function renderShowUrl($prefix = 'admin')
    {
        $route = $this->id;
        $name = 'refunds.show';

        return route($prefix . ".{$name}", $route);
    }


    /**
     * Render archive url for specific item
     * 
     * @return string/route
     */
    public function renderArchiveUrl($prefix = 'admin') 
    {
        return route($prefix . '.refunds.archive', $this->id);
    }

    /**
     * Render archive url for specific item
     * 
     * @return string/route
     */
    public function renderRestoreUrl($prefix = 'admin') 
    {
        return route($prefix . '.refunds.restore', $this->id);
    }

    public function renderApproveUrl($prefix = 'admin') {
        return route($prefix . '.refunds.approve', $this->id);
    }

    /*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/

	public static function checkIfExists($consultationID)
	{
		$user = request()->user();

		if(Refund::where(['consultation_id' => $consultationID])->exists()) {
			return true;
		}
		return false;
	}


    /**
     * Check if the item can be Archived
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
