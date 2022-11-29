<?php

namespace App\Http\Controllers\API\Team\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Models\Users\MedicalRepresentative;

use App\Helpers\FileHelpers;

use Storage;
use DB;

class ProfileController extends Controller
{
    /**
    * Update the basic user information
    *
    * @param App\Http\Requests\Care\UpdateCareInfoRequest $request
    * @return Illuminate\Http\Response
    */
   
   	public function update(Request $request) 
   	{
   		DB::beginTransaction();
   			$med_rep = $request->user();
   			$med_rep->update($request->all());
   		DB::commit();

   		return response()->json([
   			'message' => 'Updated successfuly',
        'med_rep' => $med_rep,
   			'response' => 422
   		]);
   	}

   	/**
   	* Update the profile picture
   	*
   	* @param App\Http\Requests\Care\UpdateCareInfoRequest $request
   	* @return Illuminate\Http\Response
   	*/

   	public function imageUpdate(Request $request) 
   	{
   		DB::beginTransaction();
            if(Storage::exists($request->user()->image_path)) {
                Storage::delete('public/'. $request->user()->image_path);
            }
            $image_path = FileHelpers::store($request->file('image'), 'medical_representives');

   			    $request->user()->update(['image_path' => $image_path]);

            $med_rep = $request->user();
   		DB::commit();

   		return response()->json([
   			'message' => 'Image uploaded',
   			'response' => 200,
        'med_rep' => $med_rep,
        'image_path' => url($request->user()->renderImagePath('image_path')),
   		]);
   	}

   	/**
   	* Update password
   	*
   	* @param App\Http\Requests\Care\UpdateCareInfoRequest $request
   	* @return Illuminate\Http\Response
   	*/
   
   	public function password(Request $request)
   	{
   		$request->validate([
   		    'old_password' => 'required',
   		    'password' => 'required|confirmed|min:6|confirmed',
   		    // 'password_confirmation' => 'required',
   		]);

   		if(!\Hash::check($request->old_password, $request->user()->password)) {
   		    throw ValidationException::withMessages([
   		        'old_password' => ['Old password does not match'],
   		     ]);
   		}

   		if(strcmp($request->old_password, $request->password) == 0){
   		    throw ValidationException::withMessages([
   		        'password' => ['New Password cannot be same as your current password'],
   		     ]);
   		}

   		$request->user()->update([
   		    'password' => \Hash::make($request->password),
   		]);

   		return response()->json(['message' => 'Password successfully updated']);
   	}
}
