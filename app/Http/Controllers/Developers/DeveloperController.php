<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Models\Users\Admin;
use App\Models\Users\User;

use Auth;

class DeveloperController extends Controller
{
	public function changeAccount(Request $request)
	{
		$user = null;

		switch ($request->input('guard')) {
			case 'admin':
					$user = Admin::find($request->input('id'));
				break;
			
			case 'care':
					$user = User::find($request->input('id'));
				break;
		}

		if ($user) {
			Auth::guard($request->input('guard'))->login($user);
		} else {
			throw ValidationException::withMessages([
				'id' => 'User not found',
			]);
		}

		return response()->json([
			'redirect' => url()->previous(),
		]);
	}

    public function fetchUsers()
    {
    	$admins = Admin::get();
    	$users = User::get();

    	return response()->json([
    		'admins' => $admins,
    		'users' => $users,
    	]);
    }
}
