<?php

namespace App\Models\ConsultationCalls;

use App\Extendables\BaseModel as Model;

use App\Models\Consultations\Consultation;

class ConsultationCall extends Model
{
    /*
	|--------------------------------------------------------------------------
	| @Consts
	|--------------------------------------------------------------------------
	*/



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

	public function consultation()
	{
		return $this->belongsTo(Consultation::class)->withTrashed();
	}

	public function caller()
	{
		return $this->morphToOne();		
	}

	public function receiver()
	{
		return $this->morphToOne();		
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
        $vars = $request->only(['consultation_id','receiver_id','start_time']);
        $user = request()->user();

        if (!$item) {
        	$vars['caller_id'] = $user->id;
        	$vars['caller_type'] = get_class($user);
        	$vars['receiver_type'] = self::renderReceiverType(get_class($user)),
            $item = static::create($vars);
        } else {
        	$vars['end_time'] = $request['end_time'];
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
	 * Render receiver type
	 * 
	 * @param  string $callerType
	 */
	public static function renderReceiverType($callerType)
	{
		if($callerType == 'App\Models\Users\User') {
			return 'App\Models\Users\User';
		}
		return 'App\Models\Users\Doctor';
	}

	/*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/

}
