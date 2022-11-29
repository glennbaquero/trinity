<?php

namespace App\Http\Controllers\Admin\Prescriptions;

use App\Models\Prescriptions\Prescription;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Notifications\Care\ApprovePrescription;
use App\Notifications\Care\RejectedPrescription;

use DB;
use App\Services\PushService;

class PrescriptionController extends Controller
{
    
    /**
	* Redirect to index page
	*
	* @return Illuminate\Http\Response
    */
	public function index()
	{
		return view('admin.prescriptions.index');
	}

	/**
	* Redirect to specific prescription's informative page
	*
	* @param int $id
	* @return Illuminate\Http\Response
    */
	public function show($id)
	{
		$prescription = Prescription::findOrFail($id);

		return view('admin.prescriptions.show', compact('prescription'));
	}

	/**
	* Update status
	*
	* @param Illuminate\Http\Request $request
	* @param int $id
	* @return Illuminate\Http\Response
    */
	public function update(Request $request, $id)
	{
		$prescription = Prescription::find($id);

		DB::beginTransaction();
			if($request->status == 1) {
				$prescription->user->notify(new ApprovePrescription($prescription->parent));
			} else {
				$prescription->user->notify(new RejectedPrescription($prescription->parent));
			}	

			$push = new PushService('Prescription status changed', "Please check your notification in app or in your email");
			$push->pushToOne($prescription->user);

			$prescription->update([
				'approved' => $request->status
			]);

			if ($request->status) {
				$prescription->user->products()->attach($prescription->product_id);
			}
		DB::commit();

		return response()->json([
			'message' => "You have successfully updated prescription #{$id}",
			'redirectUrl' => route('admin.prescriptions.index')
		]);
	}

	/**
	* Archive prescription
	*
	* @param int $id
	* @return Illuminate\Http\Response
    */
	public function archive($id)
	{
		$prescription = Prescription::find($id);

		DB::beginTransaction();
			$prescription->archive();
		DB::commit();

		return response()->json([
			'message' => "You have sucessfully archived prescription #{$id}"
		]);
	}

}
