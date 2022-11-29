<?php

namespace App\Http\Controllers\API\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Users\User;

use App\Models\BloodPressures\BloodPressure;
use App\Models\BloodSugars\BloodSugar;
use App\Models\HeartRates\HeartRate;
use App\Models\Bmis\Bmi;
use App\Models\Cholesterols\Cholesterol;

class MyHealthController extends Controller
{

	public function fetch(Request $request)
	{
		$user = User::find($request->patient_id);

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
				'cholesterols' => $cholesterols
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

}