<?php

namespace App\Models\Reviews;

use App\Extendables\BaseModel as Model;

use App\Models\Consultations\Consultation;
use App\Models\Users\Doctor;
use App\Models\Users\User;

class DoctorReview extends Model
{

    /*
    |--------------------------------------------------------------------------
    | @Relationships
    |--------------------------------------------------------------------------
    */
    
    public function consultation()
    {
    	return $this->belongsTo(Consultation::class)->withTrashed();
    }

    public function doctor()
    {
    	return $this->belongsTo(Doctor::class)->withTrashed();
    }

    public function reviewer()
    {
    	return $this->belongsTo(User::class, 'reviewer_id')->withTrashed();
    }


    /*
    |--------------------------------------------------------------------------
    | @Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Store of user review
     * 
     * @param  Array $request
     */
    public static function store($request)
    {
        $user_id = $request->user()->id;

        $vars = $request->except([]);
        $vars['reviewer_id'] = $user_id;

        $review = DoctorReview::create($vars);

        return $review;
    }

    /*
    |--------------------------------------------------------------------------
    | @Renders
    |--------------------------------------------------------------------------
    */

    /**
     * Render description of specified review
     * 
     * @return string
     */
    public function renderDescription()
    {
        if($this->description) {
            return $this->description;
        }

        return 'N/A';
    }

    /**
     * Render archive url of specified review
     * 
     * @return string
     */
    public function renderArchiveUrl() 
    {
        return route('admin.doctor-reviews.archive', $this->id);
    }

    /**
     * Render restore url of specified review
     * 
     * @return String
     */
    public function renderRestoreUrl() 
    {
        return route('admin.doctor-reviews.restore', $this->id);
    }

    /*
    |--------------------------------------------------------------------------
    | @Checkers
    |--------------------------------------------------------------------------
    */

}
