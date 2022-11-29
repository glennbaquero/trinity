<?php

namespace App\Http\Controllers\API\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Notifications\DeviceToken;

class DeviceTokenController extends Controller
{
    public function store(Request $request) {
        $user = $request->user();
        $token = DeviceToken::where(['deviceable_id' => $user->id, 'deviceable_type' => 'App\Models\Users\Doctor'])->first();
        if(!$token) {
            $user->deviceTokens()->create([
                'token' => $request->token,
                'platform' => $request->platform
            ]);
        } else {
            $token->update([
                'token' => $request->token,
                'platform' => $request->platform
            ]);
        }

        return response()->json([
            'token' => $request->token,
        ]);
    }

    public function update(Request $request, $device_token) {
        $token = DeviceToken::where(['deviceable_id' => $user->id, 'deviceable_type' => 'App\Models\Users\Doctor'])->first();
        $token->update($request->all());

    	return response()->json([
    		'token' => $request->token,
    	]);
    }
}
