<?php

namespace App\Http\Controllers\API\Care\Auth;

use App\Models\Users\User;
use App\Models\Users\UserSetting;
use App\Http\Requests\API\Care\Auth\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Notifications\Care\UserReferral;


use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Hash;
use Auth;
use JWTAuth;
use DB;

class RegisterController extends Controller
{
    /**
     * Registers a user
     * 
     * @param Illuminate\Http\Request
     */
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
                'mobile_number' => $request->mobile_number,
            ]);

            $body = $request->except(['first_name', 'last_name', 'email', 'mobile_number', 'password', 'password_confirmation','referrer_code']);
            $body['default'] = true;


            if($request->referrer_code){
                // find referrer information
                $referrer = User::where('referral_code',$request->referrer_code)->first();
                
                if($referrer) {
                    $referrer->referred()->syncWithoutDetaching($user->id);
                    $referrer->notify(new UserReferral($user));
                } 
            }

            $user->addresses()->create($body);
            $user->addReferralCode();

        DB::commit();

        $user->sendEmailVerificationNotification();
        
        //$token = \JWTAuth::fromSubject($user);

    	return response()->json([
            'message' => 'Registration complete. Please verify this account using this email '.$request->email
    	]);
    }

    /**
     * Registers a user via fb login
     * 
     * @param Illuminate\Http\Request
     */
    public function fbRegister(Request $request)
    {
        DB::beginTransaction();
            $user = User::where('email', $request->email)->withTrashed()->first();

            if($user) {
                if(!$user->facebook_id) {
                    $user->facebook_id = Hash::make($request->id);
                }
                $user->save();
            } else {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Str::random(40),
                    'facebook_id' => Hash::make($request->id),
                    'email_verified_at' => Carbon::now(),
                ]);
                $user->addReferralCode();
            }

        DB::commit();

        // $user->sendEmailVerificationNotification();
        
        $token = 'Bearer ' . JWTAuth::fromSubject($user);
        Auth::guard('care')->login($user);

        $settings = UserSetting::store(null, $user);
        return response()->json([
            'message' => 'Registation complete. Please choose an option to verify your account.',
            'token' => $token,
            'user' => $user,
            'settings' => $settings
        ]);
    }
}
