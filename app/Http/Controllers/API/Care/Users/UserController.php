<?php

namespace App\Http\Controllers\API\Care\Users;

use Illuminate\Http\Request;
use App\Http\Requests\API\Care\Profile\UpdateBasicInfoRequest;
use App\Http\Requests\API\Care\Profile\ChangePasswordRequest;

use App\Services\PushServiceDoctor;

use App\Helpers\FileHelpers;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Models\Users\User;
use App\Models\Products\Product;

use DB;

class UserController extends Controller
{
    
    /**
    * Update the basic user information
    *
    * @param App\Http\Requests\Care\UpdateBasicInfoRequest $request
    * @return Illuminate\Http\Response
    */
	public function updateBasicInfo(UpdateBasicInfoRequest $request)
	{
		DB::beginTransaction();
			$body = $request->except(['email', 'image']);

			if ($request->file('image')) {
				$body['image_path'] = $request->file('image')->store('profile', 'public');
			}

			if ($request->email && !User::where('email', $request->email)->first()) {
				$body['email'] = $request->email;
			}

			$request->user()->update($body);

			$user = $request->user();
			$user['image_path'] = $request->user()->image_path ? url($request->user()->renderImagePath()) : '';
		DB::commit();

		return response()->json([
			'message' => 'Info successfully updated',
			'user' => $user
		]);
	}


	/**
    * Chnage user's password
    *
    * @param App\Http\Requests\Care\Profile\ChangePasswordRequest $request
    * @return Illuminate\Http\Response
    */
    public function changePassword(ChangePasswordRequest $request)
    {
    	DB::beginTransaction();
	    	$user = $request->user();

    		$user->update([
    			'password' => Hash::make($request->new_password)
    		]);
	    DB::commit();

    	return response()->json(['message' => 'You\'ve successfully changed your password']);
    }


    public function myDoctors(Request $request)
    {
    	$user = request()->user();
    	$myDoctors = $user->doctors;

    	// if($request->filled('product_id')) {
    	// 	$product = Product::find($request->product_id);
    	// 	$specializationIDs = $product->specializations->pluck('id')->toArray();

    	// 	if(count($specializationIDs)) {
	    // 		$myDoctors = $user->doctors()->where('specialization_id', $specializationIDs)->get();
    	// 	} else {
    	// 		return null;
    	// 	}
    	// }

    	return response()->json([
    		'doctors' => $myDoctors
    	]);

    }   

    /**
     * Decline call
     * 
     * @param  Request $request
     */
    public function manageCall(Request $request)
    {
        $user = $request->user();
        $item = $user->videoCallSessionReceivable()->latest()->first();

        $doctor = $item->dispatchable;
        if($request->action == 'rejected') {
            $push = new PushServiceDoctor('Call', 'Call rejected');
        }
        
        if($request->action == 'ended') {
            $push = new PushServiceDoctor('Call', 'Call ended');
        }

        $push->pushToOne($doctor);            
        return response()->json([
            'doctor' => $doctor,
            'message' => 'Call has been ' . $request->action,
        ]);
    }

}
