<?php

namespace App\Http\Controllers\API\Doctor\Auth;

use App\Models\Users\Doctor;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Registers a user
     * 
     * @param Illuminate\Http\Request
     */
    public function register(RegisterRequest $request)
    {
        // dd($request->all());
        \DB::beginTransaction();

            $doctor = Doctor::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
                'mobile_number' => $request->mobile_number,
                'alma_mater' => $request->alma_mater,
                'place_of_practice' => $request->place_of_practice,
                'license_number' => $request->license_number,                
                'specialization_id' => $request->specialization_id,
            ]);

        \DB::commit();
        
        // $doctor->sendEmailVerificationNotification();

        $token = \JWTAuth::fromSubject($doctor);

    	return response()->json([
            // 'token' => 'Bearer ' . $token,
    		'message' => 'Registation complete, kindly check your email to verify account.',
    	]);
    }
}
