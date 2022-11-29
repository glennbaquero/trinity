<?php

namespace App\Http\Controllers\API\Care\RequestClaimReferrals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Referrals\RequestClaimReferral;

class RequestClaimReferralController extends Controller
{
	/**
	 * Request a claim referral
	 * 
	 * @param  Request $request
	 */
	public function request(Request $request)
	{
		$user = $request->user();
		if($user->checkIfAlreadyRequested($request->success_referral_id)) {
			return response()->json([
				'status' => 0,
				'message' => "You've already requested a claim for this referral."
			]);
		} else {
			$user->requestClaims()->create(['request_by' => $user->id, 'success_referral_id' => $request->success_referral_id]);
			return response()->json([
				'status' => 1,
				'message' => "Request claim has been successfully submitted"
			]);
		}
	}

}
