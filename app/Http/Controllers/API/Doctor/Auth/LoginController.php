<?php

namespace App\Http\Controllers\API\Doctor\Auth;

use App\Models\Users\Doctor;
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
        
        $doctor = Doctor::where('email', $request->email)->first();
        if(!$doctor) {
            $this->sendFailedLoginResponse();
        }

        $password = \Hash::check($request->password, $doctor->password);
        if(!$password) {
            $this->sendFailedLoginResponse();
        }

        if (!$doctor->status) {
            throw ValidationException::withMessages([
                'account' => ['Your account is not yet approved. Account must be approved first to proceed.']
            ]);
        }

        $token = \JWTAuth::fromSubject($doctor);
        auth()->guard('doctor')->login($doctor);

        return response()->json([
            'token' => 'Bearer ' . $token,
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
        auth()->guard('doctor')->logout();

        return response()->json([
            'message' => 'Logout successful',
            'response' => 200
        ]);
    }
}
