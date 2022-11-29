<?php

namespace App\Http\Controllers\API\Team\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Validation\ValidationException;
use App\Models\Users\MedicalRepresentative;

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
        
        $med_rep = MedicalRepresentative::where('email', $request->email)->first();
        if(!$med_rep) {
            $this->sendFailedLoginResponse();
        }

        $password = \Hash::check($request->password, $med_rep->password);
        if(!$password) {
            $this->sendFailedLoginResponse();
        }

        $token = \JWTAuth::fromSubject($med_rep);
        auth()->guard('med_rep')->login($med_rep);

        return response()->json([
            'token' => 'Bearer ' . $token,
            'user' => json_encode($med_rep),
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
}
