<?php

namespace App\Http\Controllers\API\Care\Schedules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Schedules\Schedule;

class ScheduleController extends Controller
{
	
	public function fetch(Request $request)
	{

		$user = $request->user();
		$schedules = Schedule::fetch($request->doctor_id, $request->date);
			
		return response()->json([
			'schedules' => $schedules
		]);
	}

}
