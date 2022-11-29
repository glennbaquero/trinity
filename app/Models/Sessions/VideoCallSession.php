<?php

namespace App\Models\Sessions;

use App\Extendables\BaseModel as Model;

use App\Models\Consultations\Consultation;

class VideoCallSession extends Model
{
    
    /*
     * Relationship
     */
    
    public function dispatchable() {
        return $this->morphTo();
    }
    
    public function receivable() {
        return $this->morphTo();
    }

    public function consultation() {
        return $this->belongsTo(Consultation::class, 'consultation_id');   
    }

    /**
     * Store specified resource from storage
     * 
     * @param  Array $request
     * @return  object $item
     */
    public static function store($request)
    {
		$user = request()->user();

        $item = self::create([
			'dispatchable_id' => $user->id,
			'dispatchable_type' => get_class($user),
			'receivable_id' => $request['receiver_id'],
			'receivable_type' => self::renderReceiverType(get_class($user)),
			'session' => $request['session'],
			'token' => $request['token'],
            'consultation_id' => $request['consultation_id']
		]);

        return $item;
    }

    /**
     * Render receiver type
     * 
     * @param  string $senderType
     */
    public static function renderReceiverType($senderType)
    {
    	if($senderType == 'App\Models\Users\User') {
    		/** Make doctor the receiver */
    		return 'App\Models\Users\Doctor';
    	}
    	return 'App\Models\Users\User';
    }
}
