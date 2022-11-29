<?php

namespace App\Models\PersonalInformations;

use App\Extendables\BaseModel as Model;

use App\Models\Users\User;
use App\Models\ConsultationChats\ConsultationChat;

class PersonalInformation extends Model
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

    public function user()
    {
		return $this->belongsTo(User::class);    	
    }

    /*
    |--------------------------------------------------------------------------
    | @Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Store resource to storage
     * 
     * @param  array $request
     */
    public static function store($request) 
    {
        $user = request()->user();

    	if($request->condition) {
	        $item = self::updateOrCreate([
                'user_id' => $user->id
            ], [
                'condition' => $request->condition
            ]);} else if($request->weight && $request->weight_units) {
                $item = self::updateOrCreate([
                'user_id' => $user->id
            ], [
                'weight' => $request->weight,
                'weight_units' => $request->weight_units
            ]);} else if(($request->height_feet && $request->height_inches) || $request->height_inches == 0) {
                $item = self::updateOrCreate([
                'user_id' => $user->id
            ], [
                'height_feet' => $request->height_feet,
                'height_inches' => $request->height_inches
            ]);}

    	return $item;
    }

    /**
     * Share resource from storage
     * 
     * @param  $request
     */
    public function share($request) 
    {

        $info = $this->constructPersonalInfo();
        $request['message'] = $info;

        ConsultationChat::store($request, false);

    }

    /**
     * Construct personal info message
     * 
     */
    public function constructPersonalInfo() 
    {
        $message = "<p>MyHealth Record: </p>";
        $message .= "<p><b>Height:</b> {$this->height_feet}' {$this->height_inches} ft</p>";
        $message .= "<p><b>Weight:</b> {$this->weight} {$this->weight_units}</p>";
        $message .= "<p><b>Other conditions:</b> {$this->condition}</p>";

        return $message;
    }

    /*
    |--------------------------------------------------------------------------
    | @Renders
    |--------------------------------------------------------------------------
    */
   

    /*
    |--------------------------------------------------------------------------
    | @Checkers
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user has personal information
     * 
     * @return boolean
     */
    public static function check()
    {
        $user = request()->user();
        if($user->personal_information) {
            return true;
        }
    }

}
