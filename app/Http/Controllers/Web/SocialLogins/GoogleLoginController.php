<?php

namespace App\Http\Controllers\Web\SocialLogins;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Validation\ValidationException;

use App\Models\Users\User;
use App\Models\Users\UserSetting;

use Carbon\Carbon;

use Socialite;
use Hash;
use JWTAuth;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GoogleLoginController extends Controller
{
    /**
     * @Google Login
     */
    public function login(Request $request) {
        $vars = $request->only(['userId', 'givenName', 'familyName', 'email']);
        $user = $this->authenticate($vars);
        $settings = UserSetting::store(null, $user);

        return response()->json([
            'user' => $user,
            'settings' => $settings
        ]);
    }

    protected function authenticate($vars) {
    	$token = null;

        $id = $vars['userId'];
        $email = $vars['email'];

        $user = User::where('email', $email)->first();

        if ($user) {
            if (!$user->google_id) {
                $user->google_id = Hash::make($id);
                $user->email_verified_at = now();
	            $user->save();
            }
        } else {

	        $vars = [
	            'first_name' => $vars['givenName'],
	            'last_name' => $vars['familyName'],
	            'email' => $email,
	            'google_id' => Hash::make($id),
	        ];

            $vars = array_merge($vars, [
                'password' => Str::random(40),
                'email_verified_at' => now(),
            ]);

            $user = User::create($vars);


        }

        if (Hash::check($id, $user->google_id)) {
            $token = 'Bearer ' . JWTAuth::fromSubject($user);
        } else {
            throw ValidationException::withMessages([
                'email' => "Credentials doesn't seem to match your social media account."
            ]);
        }

        $user['token'] = $token;

        return $user;
    }
}
