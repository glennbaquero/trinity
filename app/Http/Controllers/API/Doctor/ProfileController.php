<?php

namespace App\Http\Controllers\API\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Services\PushService;

use App\Http\Requests\API\Doctor\Profile\DoctorProfileStoreRequest;

use App\Models\Users\Doctor;
use DB;

class ProfileController extends Controller
{
    /**
     * Update doctor's information
     * 
     * @param Illuminate\Http\Request
     */
    public function update(DoctorProfileStoreRequest $request)
    {
        
        \DB::beginTransaction();
            $user = request()->user();
            $user->update($request->except(['image_path']));
                
            if($request->hasFile('image_path')) {
                $path = $request->file('image_path')->store('doctor-images', 'public');
                $user->image_path = $path;
                $user->save();
            }

        \DB::commit();

        return response()->json([
            'message' => 'Profile successfully updated!',
            'doctor' => $user,
        ]);
    }

    /**
     * Update doctor's online status
     * 
     * @param Illuminate\Http\Request
     */
    public function updateOnlineStatus(Request $request)
    {
        \DB::beginTransaction();
        
            $user = request()->user();
            $user->online = $request->online;
            $user->save();

        \DB::commit();

        return response()->json([
            'message' => 'Profile successfully updated!',
            'doctor' => $request->user(),
        ]);
    }

    /**
     * Udpdate doctor's password
     * 
     * @param Illuminate\Http\Request
     */
    public function password(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
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
            'password' => \Hash::make($request->password)
        ]);

        return response()->json(['message' => 'Password successfully updated']);
    }


    public function uploadSignature(Request $request)
    {
        $user = request()->user();

        DB::beginTransaction();

            $user->storeSignature($request);

        DB::commit();

        return response()->json([
            'message' => 'Youre signature has been successfully uploaded',
            'doctor' => $request->user(),
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

        $patient = $item->dispatchable;
        
        if($request->action == 'rejected') {
            $push = new PushService('Call', 'Call rejected');            
        }
        
        if($request->action == 'ended') {
            $push = new PushService('Call', 'Call ended');
        }

        $push->pushToOne($patient);
        
        return response()->json([
            'patient' => $patient,
            'message' => 'Call has been '. $request->action,
        ]);

    }

}
