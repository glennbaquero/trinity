<?php

namespace App\Http\Controllers\API\Care\Refunds;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Refunds\Refund;
use App\Models\Consultations\Consultation;


use DB;

class RefundController extends Controller 
{

	public function store(Request $request)
	{
		$consultation = Consultation::findOrFail($request->consultation_id);

		if($consultation->canRefund()) {

			if(Refund::checkIfExists($consultation->id)) {

				return response()->json([
					'status' => 3,
					'title' => 'Refund Request',
					'message' => 'Request has been already filed. Kindly wait for us to process your request',
				]);

			} else {

				/** Start transaction */
				DB::beginTransaction();

					/** Store refund request */
					Refund::store($request);

				/** End transaction */
				DB::commit();

			}



		} else {

			return response()->json([
				'title' => 'Refund Request',
				'status' => 2,
				'message' => 'Consultation is not refundable',
			]);

		}

		return response()->json([
			'status' => 1,
			'title' => 'Refund Request',
			'message' => 'Refund request has been successfully sent. Kindly wait for us to process your request. Thank you!',
		]);
	}

}


