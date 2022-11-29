<?php

namespace App\Http\Controllers\API\Care\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Validation\ValidationException;

use App\Models\Users\User;
use App\Models\Users\UserSetting;

use Socialite;
use Hash;
use Auth;
use JWTAuth;
use Illuminate\Support\Str;

class FacebookLoginController extends Controller
{
     const scopes = [
        //
    ];

    const fields = [
        'id',
        'first_name', // Default
        'last_name', // Default
        'email', // Default
    ];

    /**
     * @Facebook Login
     */
    public function login() {
    	return $this->socialLogin('facebook');
    }

    public function facebookCallback(Request $request) {
        $provider = 'facebook';
    	$user = $this->socialCallback($provider);

        return $this->redirectCallback($request, $provider, $user);
    }

    /**
     * @Socialite
     */
    protected function socialLogin($provider) {
	    $result = Socialite::driver($provider)->scopes(static::scopes)->fields(static::fields)->redirect();
        return $result;
    }

    protected function socialCallback($provider) {
        $user = Socialite::driver($provider)->scopes(static::scopes)->fields(static::fields)->user();

        return $this->authenticate($provider, $user);
    }

    protected function redirectCallback(Request $request, $provider, $user) {
        
        $settings = UserSetting::store(null, $user);

        return view('web.facebook.social-callback', [
            'provider' => $provider,
            'user' => $user,
            'settings' => $settings
        ]);
    }

    protected function authenticate($provider, $socialite_user) {
        $token = null;
        $vars = [];
        $data = [];
	    $column = 'facebook_id';
	    $id = $socialite_user->id;
	    $email = $socialite_user->email;

        $user = User::where('email', $email)->withTrashed()->first();

        if ($user) {
            if (!$user->facebook_id) {
                $user->facebook_id = Hash::make($id);
            }

            $user->save();

        } else {
            $socialite_user = json_decode(json_encode($socialite_user));
            $userVars = $socialite_user->user;
            $vars = [
                'first_name' => $userVars->first_name,
                'last_name' => $userVars->last_name,
                'email' => $email,
                'facebook_id' => Hash::make($userVars->id),
                'password' => Str::random(40),
                'email_verified_at' => now(),
            ];

            $user = User::create($vars);            
        }

        if (Hash::check($id, $user[$column])) {
            $token = 'Bearer ' . JWTAuth::fromSubject($user);
            Auth::guard('care')->login($user);
        } else {
            throw ValidationException::withMessages([
                'email' => "Credentials doesn't seem to match your social media account."
            ]);
        }

        // array_push($data, [
        // 	'token' => $token,
        // 	'user' => $user
        // ]);
        $user['token'] = $token;
        return $user;
    }
}
