<?php

namespace App\Http\Controllers\API\Care\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\BloodPressures\BloodPressure;
use App\Models\BloodSugars\BloodSugar;
use App\Models\HeartRates\HeartRate;
use App\Models\Bmis\Bmi;
use App\Models\Cholesterols\Cholesterol;

use DB;

class MyHealthController extends Controller
{

	/**
	 * Fetch my health resources
	 * 
	 * @param  Request $request 
	 */
	public function fetch(Request $request) 
	{

		$user = $request->user();

		$bloodPressures = BloodPressure::fetchLatest($user); 
		$bloodSugars = BloodSugar::fetchLatest($user);
		$heartRates = HeartRate::fetchLatest($user);
		$bmis = Bmi::fetchLatest($user);
		$cholesterols = Cholesterol::fetchLatest($user);

		$bloodPressureChart = BloodPressure::fetchChart($request, $user);
		$heartRateChart = HeartRate::fetchChart($request, $user);
		$bmiChart = Bmi::fetchChart($request, $user);
		$bloodSugarChart = BloodSugar::fetchChart($request, $user);
		$cholesterolChart = Cholesterol::fetchChart($request, $user); 

		return response()->json([
			'data' => [
				'bloodPressures' => $bloodPressures,
				'bloodSugars' => $bloodSugars,
				'heartRates' => $heartRates,
				'bmis' => $bmis,
				'cholesterols' => $cholesterols,
				'personal_information' => $user->personal_information
			],
			'charts' => [
				'bloodPressure' => $bloodPressureChart,
				'heartRate' => $heartRateChart,
				'bmi' => $bmiChart, 
				'bloodSugar' => $bloodSugarChart,
				'cholesterol' => $cholesterolChart
			]
		]);

	}

	/**
	 * Update MyHealth module
	 * 
	 * @param  Request $request
	 */
	public function update(Request $request) 
	{
		$user = $request->user();
			
		switch ($request->type) {
			case 'bp':
				$message = $this->updateBp($request, $user);
				break;
			
			case 'bs':
				$message = $this->updateBs($request, $user);
				break;

			case 'hr':
				$message = $this->updateHr($request, $user);
				break;

			case 'bmi':
				$message = $this->updateBmi($request, $user);
				break;

			case 'chl':
				$message = $this->updateChl($request, $user);
				break;
		}

		return response()->json([
			'message' => $message,
		]);

	}

	/**
	 * Update blood pressure
	 * 
	 * @param  Request $request
	 */
	public function updateBp($request, $user)
	{

		
		$vars = [
			'systole' => $request->systole,
			'diastole' => $request->diastole
		];

		$request->validate([
			'systole' => 'required|numeric|min:1',
			'diastole' => 'required|numeric|min:1',
		]);
		
		/** Start transaction */
		DB::beginTransaction();
		
			$user->blood_pressures()->create($vars);

		/** End transaction */
		DB::commit();

		return "Blood pressure updated!"; 
	}

	/**
	 * Update blooed sugar
	 * 
	 * @param  Request $request
	 */
	public function updateBs($request, $user) 
	{
		$vars = [
			'value' => $request->value,
		];
		
		$request->validate([
			'value' => 'required|numeric|min:20',
		]);
		
		/** Start transaction */
		DB::beginTransaction();

			$user->blood_sugars()->create($vars);

		/** End transaction */
		DB::commit();

		return "Blood sugar updated!"; 
	}
	
	/**
	 * Update heart rate
	 * 
	 * @param  Request $request
	 */
	public function updateHr($request, $user) 
	{
		$vars = [
			'value' => $request->value,
		];

		$request->validate([
			'value' => 'required|numeric|min:1',
		]);
		
		/** Start transaction */
		DB::beginTransaction();

			$user->heart_rates()->create($vars);
		
		/** End transaction */
		DB::commit();

		return "Heart rate updated!"; 

	}

	/**
	 * Update bmi
	 * 
	 * @param  Request $request
	 */
	public function updateBmi($request, $user) 
	{
		$request->validate([
			'height' => 'required|numeric|min:1',
			'weight' => 'required|numeric|min:1',
		]);
		$heightInMeter = $request->height / 100;

		$bmi = ($request->weight / ($heightInMeter * $heightInMeter));
		$bmi = round($bmi, 2);
		
		$vars = [
			'value' => $bmi,
		];

		/** Start transaction */
		DB::beginTransaction();
			
			$user->bmis()->create($vars);		
		
		/** End transaction */
		DB::commit();

		return "BMI updated!"; 

	}

	/**
	 * Update cholesterol
	 * 
	 * @param  Request $request
	 */
	public function updateChl($request, $user) 
	{

		$request->validate([
			'ldl' => 'required|numeric|min:1',
			'hdl' => 'required|numeric|min:15',
			'total' => 'required|numeric|min:15',
		]);

		$vars = [
			'ldl' => $request->ldl,
			'hdl' => $request->hdl,			
			'total' => $request->total,
		];

		/** Start transaction */
		DB::beginTransaction();

			$user->cholesterols()->create($vars);

		/** End transaction */
		DB::commit();

		return "Cholesterol updated!"; 			

	}

	/**
	 * Update reviewers
	 * 
	 * @param  Request $request
	 */
	public function updateReviewers(Request $request)
	{
		$user = $request->user();

		$oldReviwers = $user->reviewers->pluck('id')->toArray();

		/** Start transaction */
		DB::beginTransaction();

			$user->reviewers()->sync($request->doctors);

		/** End transaction */
		DB::commit();

		$user->sendReviewerNotification($oldReviwers, $request->doctors);

		return response()->json([
			'message' => 'Reviewers has been successfully updated',
			'reviewers' => $user->reviewers()->pluck('id')->toArray(),
		]);

	}

}