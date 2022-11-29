<?php
namespace App\Http\Controllers\API\Care\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Users\User;
use App\Models\Users\UserSetting;
use DB;

class UserSettingController extends Controller
{
    
	public function store(Request $request)
    {	
        $setting = UserSetting::store($request);
		return response()->json([
			'settings' => $setting,
			'message' => "Settings has been successfully updated",
		]);
    
	}

}
