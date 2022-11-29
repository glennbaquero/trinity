<?php

namespace App\Models\Users;

use App\Models\Users\User;

use App\Extendables\BaseModel as Model;

use App\Traits\HelperTrait;

use Carbon\Carbon;

use Illuminate\Http\Request;

class UserSetting extends Model
{
    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
    
	public function user(){

       return $this->belongsTo(User::class);
    }

     /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function store($request, $user = null)
    {	
		$user = $user ? $user: $request->user();
		
		$setting = UserSetting::where('user_id', $user->id)->first();
		
		if(!$setting){
			$setting = UserSetting::create([
				'user_id' => $user->id,  
			]);					
		} else {


			if($request) {
				$vars = [];

				if(isset($request->promo)) {
					$vars['promo'] = $request->promo;
				}

				if (isset($request->uploaded_article)) {
					$vars['uploaded_article'] = $request->uploaded_article;
				}

				if(isset($request->share_records)) {
					$vars['share_records'] = $request->share_records;
				} 

				$setting->update($vars);
			}

		}
		return $setting;

	}

}