<?php

namespace App\Http\Controllers\API\Doctor\Payouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Payouts\Payout;
use App\Models\Users\Doctor;

use DB;

class PayoutController extends Controller
{

	/**
	 * Fetch resources from storage
	 * 
	 * @return \Illuminate\Http\Response
	 */
    public function fetch(Request $request)
    {
    	$doctor = request()->user();
    	$payouts = [];
        $earnings = [];
    	$credits = $doctor->countCredits(false);

        if ($request->type == 'earnings') {
            $earnings = $doctor->fetchEarnings($request->page);            
        } 

        if ($request->type == 'payouts') {
            $payouts = $doctor->payouts()->latest()->paginate(10, ['*'], 'page', $request->page);
        }

    	return response()->json([
    		'payouts' => $payouts,
            'earnings' => $earnings,
    		'credits' => $credits
    	]); 
    }

    /**
     * Store resources from storage
     * 
     * @param  array $request
	 * @return \Illuminate\Http\Response
     */
    public function request(Request $request)
    {
    	$doctor = request()->user();
        $title = null;
        $message = null;

    	/** Start transaction */
    	DB::beginTransaction();

            if(Payout::checkPending($doctor->id)) {
                $title = 'A payout request already exists';
                $message = 'You still have a pending payout request for approval';
            } else {
		        $doctor->createPayout($request->value);
                $title = 'Payment Request has been sent';
                $message = 'Processing will take (10) business days';
            }

		/** End Transaction */
		DB::commit();

		return response()->json([
			'title' => $title,
			'message' => $message
		]);
    }
}
