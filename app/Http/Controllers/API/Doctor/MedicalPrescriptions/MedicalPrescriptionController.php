<?php

namespace App\Http\Controllers\API\Doctor\MedicalPrescriptions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MedicalPrescriptions\MedicalPrescription;
use App\Models\Products\Product;

use DB;

class MedicalPrescriptionController extends Controller
{

	/**
	 * Fetch resources from storage
	 * 
	 * @param  Request $request 
	 */
	public function fetch(Request $request)
	{
		$prescription = MedicalPrescription::fetch($request->consultation_id);
		$products = Product::get(['id', 'name']);

		return response()->json([
			'prescription' => $prescription,
			'products' => $products
		]);
	}

	/**
	 * Store resource to storage
	 * 
	 * @param  Request $request
	 */
	public function store(Request $request)
	{	
		/** Start transaction */
		DB::beginTransaction();

			/** Store item */
			MedicalPrescription::store($request);

		/** End transaction */
		DB::commit();


		return response()->json([
			'status' => 1,
			'title' => 'Medical Prescription',
			'message' => 'Medical Prescription has been successfully saved'
		]);

	}
}
