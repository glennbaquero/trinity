<?php

namespace App\Http\Controllers\API\Care\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Users\User;
use App\Models\Users\UserSetting;

use JWTAuth;
use Illuminate\Support\Str;

class AppleLoginController extends Controller
{
	public function login(Request $request) 
	{

		$user = User::where('email', $request->email)->orWhere('apple_id', $request->social_id)->first();
		$settings = null;
		if(!$user) {
			$vars = [
				'first_name' => $request->first_name,
				'last_name' => $request->last_name,
				'email' => $request->email,
				'apple_id' => $request->social_id,
	            'email_verified_at' => now(),
                'password' => Str::random(40)	            		 
			];
			$user = User::create($vars);
		}

		if(!$user->user_setting) {
	        $settings = UserSetting::store(null, $user);
		} else {
			$settings = $user->user_setting;
		}

        $token = 'Bearer ' . JWTAuth::fromSubject($user);		
		$user['token'] = $token;

        return response()->json([
            'user' => $user,
            'settings' => $settings
        ]);

	}
}
