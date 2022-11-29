<?php

namespace App\Http\Controllers\API\Care\Auth;

use App\Models\Users\User;
use App\Models\Users\UserSetting;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Login user and fetch authenticated token
     * 
     * @param Illuminate\Http\Request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            $this->sendFailedLoginResponse();
        }

        $password = \Hash::check($request->password, $user->password);

        if (!$password) {
            $this->sendFailedLoginResponse();
        }
      
        if (($user && $password) && !$user->email_verified_at) {
            throw ValidationException::withMessages([
                'account' => ['Your account is not yet verified. Please verify your account using the registered email.']
            ]);
        }

        /* if (($user && $password) && !$user->verification_image_path && !$user->approved) {
            throw ValidationException::withMessages([
                'account' => ['Your account is not yet verified. Please select an option to verify your account.']
            ]);
        } */

        /* if (($user && $password) && $user->verification_image_path && $user->approved === null) {
            throw ValidationException::withMessages([
                'approval' => ['You will receive an email if your account is approved or denied.']
            ]);
        } */

        /* if (($user && $password) && $user->verification_image_path && $user->approved === 0) {
            throw ValidationException::withMessages([
                'rejected' => ['Sorry, your account was rejected.']
            ]);
        } */

        $token = \JWTAuth::fromSubject($user);
        auth()->guard('care')->login($user);
        
        $settings = UserSetting::store(null, $user);
       
        return response()->json([
            'token' => 'Bearer ' . $token,
            'user' => [
                'user_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'mobile_number' => $user->mobile_number,
                'image_path' => url($user->renderImagePath()),
                'code' => $user->referral_code
            ],
            'settings'=> $settings,
        ]);
    }

    /**
     * Send failed login message
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendFailedLoginResponse()
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Logout User
     * 
     * @param Illuminate\Http\Request
     */
    
    public function logout(Request $request)
    {
        auth()->guard('care')->logout();

        return response()->json([
            'message' => 'Logout successful',
            'response' => 200   
        ]);
    }
}
