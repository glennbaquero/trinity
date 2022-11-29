<?php

namespace App\Http\Controllers\API\Care\Auth;

use App\Models\Users\User;
use App\Models\Users\Doctor;

use Illuminate\Http\Request;
use App\Http\Requests\API\Care\Auth\VerificationRequest;
use App\Http\Controllers\Controller;

use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class VerificationController extends Controller
{

	/**
	* Verify the entered or scanned QR ID
	*
	* @param Illuminate\Http\Request $request
	* @param Illuminate\Http\Response
	* @throws Illuminate\Validation\ValidationException
	*/
	public function verifyQr(Request $request)
	{
		$doctor = Doctor::where('qr_id', str_replace('"', '', $request->qr_id))->first();

		if (!$doctor) {
			throw ValidationException::withMessages([
				'qr_id' => ['Doctor with QR ID does not exist']
			]);
		}

		$user = User::where('email', $request->email);
		
		$user->first()->doctors()->attach($doctor->id);
		$user->update(['approved' => 1, 'email_verified_at' => Carbon::now()]);

		$token = \JWTAuth::fromSubject($user->first());
		auth()->guard('care')->login($user->first());

		return response()->json([
			'token' => 'Bearer ' . $token,
			'user' => $user->first()
		]);
	}


	/**
	* Verify the uploaded prescription image
	*
	* @param Illuminate\Http\Request $request
	* @param Illuminate\Http\Response
	*/
	public function verifyPrescription(Request $request)
	{
		if (!$request->prescription) {
			throw ValidationException::withMessages([
				'prescription' => ['Please upload a prescription image']
			]);
		}

		$user = User::where('email', $request->email);
		$prescription = $request->file('prescription')->store('prescriptions', 'public');
		
		$user->update(['verification_image_path' => $prescription]);

		return response()->json([
			'message' => 'Verification in process. You will be notified when you are approved or rejected.'
		]);
	}

	
	public function verifyEmail(Request $request)
	{
		$email = $request->email;

		$user = User::where('email',$email)->first();

		if($user){
			$user->sendEmailVerificationNotification();
		}	
	
	}

	

}
