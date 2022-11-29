<?php

namespace App\Http\Controllers\API\Doctor\OnlineConsultations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Consultations\Consultation;
use App\Models\Schedules\Schedule;

use DB;

class OnlineConsultationController extends Controller
{

    /**
     * Fetch resources from storage
     * 
     * @param  Request $request 
     */
	public function fetch(Request $request)
	{
    	$doctor = request()->user();
        $schedules = [];
        $approved_schedules = [];
        $credits = 0;
        $unreadMessages = 0;
        $type = $request->type == 'all' ? [Consultation::BOOKING, Consultation::CHAT] : Consultation::BOOKING;

        if($request->action != 'consultation') {

        	$consultations = Consultation::fetch('doctor_id', [Consultation::PENDING, Consultation::APPROVED], $request->date, $type);
            $credits = $doctor->countCredits(true);
            $schedules = $doctor->schedules()->whereDate('date', $request->date)->get();
            $schedules = Schedule::formatItems($schedules); 
            $approved_schedules = collect(Consultation::where(['doctor_id' => $doctor->id])->whereIn('status', [ Consultation::APPROVED, Consultation::COMPLETED ])->get())
                                ->map(function($schedule) {
                                    return $schedule->date->format('Y-m-d');
                                });
        } else {
            $consultations = Consultation::fetch('doctor_id', [Consultation::APPROVED], 'greater-to-now', $type);
        }


    	return response()->json([
    		'credits' => $credits,
    		'schedules' => $schedules,
            'approved_schedules' => $approved_schedules,
    		'consultations' => $consultations,
    	]);

	}

}