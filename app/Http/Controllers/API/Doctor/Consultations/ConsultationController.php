<?php

namespace App\Http\Controllers\API\Doctor\Consultations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Consultations\Consultation;

use DB;

class ConsultationController extends Controller
{

	/**
	 * Fetch consultation history from storage
	 * 
	 * @return Illuminate\Http\Response
	 */
	public function fetchConsultationHistory(Request $request)
	{
        $consultation_history = Consultation::fetch('doctor_id', [Consultation::COMPLETED, Consultation::DISAPPROVED, Consultation::CANCELLED, Consultation::REFUNDED], null, 'null', true, $request->page);

		return response()->json([
            'consultation_history' => $consultation_history,
		]);
	}

	/**
	 * Fetch chat request from storage
	 * 
	 * @param  Request $request
	 */
	public function fetchChatRequests(Request $request)
	{
		$user = $request->user();
		$requests = $user->fetchChatRequests();

		return response()->json([
			'requests' => $requests
		]);

	}

	/**
	 * Update consultation status
	 * 
	 * @param  Request $request
	 */
	public function updateStatus(Request $request)
	{
		$remaining = $request->remaining;
		$user = $request->user();
		if($request->type == 'consultation_number') {
			$consultation = Consultation::where(['consultation_number' => $request->consultation_number, 'doctor_id' => $user->id])->first();
		} else {
			$consultation = Consultation::where(['id' => $request->consultation_id, 'doctor_id' => $user->id])->first();
		}
		$patient_id = $consultation->value('user_id');

		switch ($request->status) {
			case 'accepted':
				$status = Consultation::APPROVED;
				break;

			case 'declined':
				$status = Consultation::DISAPPROVED;
				break;

			case 'completed':
				$status = Consultation::COMPLETED;
				break;
		}

		if($request->status == 'accepted') {
			if(!Consultation::hasSufficientCredits('', 1, $patient_id)) {
				return response()->json([
					'isValid' => 'false',
					'title' => 'Cannot accept this consultation',
					'message' => 'The patient has insufficient credits',
				]);
			}

			if(!Consultation::checkCurrentConsultation($user)) {
				return response()->json([
					'isValid' => 'false',
					'title' => 'Cannot accept this consultation',
					'message' => 'Maximum count of ongoing consultation has been reached',
				]);
			}
		}

		/** Start transaction */
		DB::beginTransaction();

			/** Set status */
			$consultation->setStatus($status, $remaining);	

		/** End transaction */
		DB::commit();

		return response()->json([
			'status' => 1,
			'title' => 'Chat Request',
			'message' => 'Chat request has been successfully update',
			'consultation' => $consultation,
			'patient' => $consultation->user 
		]);
	}

	/**
	 * Send the user a notification when the doctor has entered the chat page
	 * 
	 * @param  Request $request
	 */
	public function sendNotification(Request $request)
	{
        $consultation = Consultation::find($request->consultation_id);
        $consultation->sendArrivalPushNotif($request->isUser);  
	}

}
