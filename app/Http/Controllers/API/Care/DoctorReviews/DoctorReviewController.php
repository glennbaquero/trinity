<?php

namespace App\Http\Controllers\API\Care\DoctorReviews;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Reviews\DoctorReview;
use App\Models\Users\Doctor;

use DB;

class DoctorReviewController extends Controller
{	
	/**
	 * Get reviews of specified doctor
	 * 
	 * @param  Request $request
	 */
	public function getReviews(Request $request) 
	{
		$doctor = Doctor::find($request->doctor_id);
		$reviews = $doctor->fetchReviews($request->reviews_count);

		return response()->json([
			'results' => $reviews
		]);
	}

	/**
	 * Store review for specified doctor
	 * 
	 * @param  Request $request 
	 */
	public function storeReview(Request $request) 
	{

		/** Start transaction */
		DB::beginTransaction();

			DoctorReview::store($request);

		/** End transaction */
		DB::commit();

		return response()->json([
			'message' => 'Your review has been successfully submitted'
		]);
	}
}

