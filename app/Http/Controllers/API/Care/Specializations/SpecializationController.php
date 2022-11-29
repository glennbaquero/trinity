<?php

namespace App\Http\Controllers\API\Care\Specializations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Specializations\Specialization;
use App\Models\Users\Doctor;
use Carbon\Carbon;

class SpecializationController extends Controller
{
    /**
    * Fetching of specialization
    *
    * @return array of objects
    */
    public function fetch(Request $request)
    {
        $specializations = Specialization::orderBy('order', 'asc')->get();

        return response()->json([
            'specializations' => $specializations
        ]);
    }

    /**
    * Fetching of doctors by selected specializations
    * 
    * @return array of objects
    */
    public function fetchDoctor(Request $request)
    {
        $doctors = new Doctor;

        if($request->specialization_id) {
            $doctors = $doctors->where('specialization_id', $request->specialization_id)->where('consultation_fee','>',0);
        }
        
        if($request->filled('name')) {
            $doctors =  $doctors->where('first_name', 'like', '%'.$request->name.'%')
            ->orWhere('last_name', 'like', '%'.$request->name.'%');
        }

        if($request->filled('availability')) {
            $availability = $request->availability;
            if($availability != 'All') {
                $doctors = $doctors->whereHas('schedules', function($schedule) use($availability) {
                    switch ($availability) {
                        case 'Morning':
                        $schedule->where('start_time', '<=', '11:59:59');
                        break;
                        case 'Afternoon':
                        $schedule->where('start_time', '>=', '12:00:00')->where('start_time', '<=', '17:59:59');
                        break;
                        case 'Evening':
                        $schedule->where('start_time', '>=', '18:00:00')->where('start_time', '<=', '24:59:59');
                        break;
                        default:
                        break;
                    }
                });
            }
        }

        if($request->filled('status')) {
            $status = $request->status;
            switch ($status) {
                case 'All':
                    $doctors = $doctors;
                break;
                case 'Online':
                    $doctors = $doctors->where('online', true);
                break;
                case 'Offline':
                    $doctors = $doctors->where('online', false);
                break;
                case 'Busy':
                    $doctors = $doctors->where('status', 2);
                break;
            }
        }

        // Distribute rating using array mapping
        $doctors = $doctors->where('consultation_fee','>',0)->get();

        $doctors = $doctors->map(function($doctor){
            $doctor->ratings = $doctor->computeRatings();
            return $doctor;
        });

        $doctors = $doctors->filter(function($doctor){
            if($doctor->consultation_fee > 0) {
                return $doctor;
            }
        });
        
        return response()->json([
            'doctors' => $doctors
        ]);
    }
}
