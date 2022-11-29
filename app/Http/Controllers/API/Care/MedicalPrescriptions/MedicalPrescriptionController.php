<?php

namespace App\Http\Controllers\API\Care\MedicalPrescriptions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MedicalPrescriptions\MedicalPrescription;
use App\Models\Consultations\Consultation;
use App\Models\Carts\Cart;

use DB;
use PDF;
use Storage;

class MedicalPrescriptionController extends Controller
{

	/**
	 * Fetch resources from storage
	 * 
	 * @param  Request $request 
	 */
	public function fetch(Request $request)
	{
		$consultation = Consultation::find($request->consultation_id);
		$prescription = MedicalPrescription::fetch($consultation->id);
		$review = $consultation->hasReview();

		return response()->json([
			'prescription' => $prescription,
			'review' => $review ? false: true
		]);
	}

	/**
	 * Check prices function
	 * Note: this function was also add to cart function
	 * 
	 * @param  Request $request
	 */
	public function checkPrice(Request $request) 
	{
		$consultation = Consultation::find($request->consultation_id);
		$prescription = MedicalPrescription::where('consultation_id', $request->consultation_id)->first();
		$meds = $prescription->meds;

		foreach ($meds as $key => $med) {
			if($med->product_id) {

				/** Start transaction */
				DB::beginTransaction();

					/** Add item to cart */
					Cart::addToCart($med->product_id, 1, $consultation->doctor_id);

				/** End trasanction */
				DB::commit();
			}
		}

		return response()->json([
			'message' => 'Item added to cart'
		]);

	}


	public function download(Request $request)
	{
		$consultation = Consultation::find($request->consultation_id);
		$prescription = $consultation->prescription;

		$pdf = PDF::loadView('admin.medical-prescriptions.print', [ 'consultation' => $consultation, 'prescription' => $prescription ]);

		Storage::put('public/pdf/'. $consultation->consultation_number. '.pdf', $pdf->output());
		$path = url('/') . Storage::url('pdf/'. $consultation->consultation_number. '.pdf');

		return response()->json([
			'path' => $path
		]);
	}

}
