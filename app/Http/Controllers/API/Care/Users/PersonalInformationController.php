<?php

namespace App\Http\Controllers\API\Care\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\PersonalInformations\PersonalInformation;

use DB;

class PersonalInformationController extends Controller
{	

	/**
	 * Store resources to storage
	 * 
	 * @param  Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$user = request()->user();
		
		/** Start transaction */
		DB::beginTransaction();

			PersonalInformation::store($request);
			
		/** End transaction */
		DB::commit();


		return response()->json([
			'message' => 'Personal information updated'
		]);

	}

	/**
	 * Share user personal information
	 * 
	 * @param  Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function share(Request $request)
	{
		$user = $request->user();

		if(!PersonalInformation::check()) {
			return response()->json([
				'exists' => false
			]);
		} else {

			$personalInfo = PersonalInformation::where(['user_id' => $user->id])->first();
			$personalInfo->share($request);

		}

		return response()->json([
			'message' => 'Personal information shared',
			'exists' => true
		]);

	}
}