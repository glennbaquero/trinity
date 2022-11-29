<?php

namespace App\Models\Schedules;

use App\Extendables\BaseModel as Model;

use App\Jobs\API\Doctors\ProcessSchedule;

use App\Models\Users\Doctor;
use App\Models\Consultations\Consultation;

use Carbon\Carbon;

class Schedule extends Model
{
	protected $dates = ['date'];
    /*
	|--------------------------------------------------------------------------
	| @Consts
	|--------------------------------------------------------------------------
	*/



	/*
	|--------------------------------------------------------------------------
	| @Attributes
	|--------------------------------------------------------------------------
	*/

	protected $appends = ['doctor_name', 'fee'];

	/**
	 * Get doctor name attribute
	 * 
	 */
	public function getDoctorNameAttribute()
	{
		if($this->doctor) {
			return $this->doctor->renderName();
		}
	}


	/**
	 * Get fee attribute
	 *
	 */
	public function getFeeAttribute() 
	{
		if($this->doctor) {
			return $this->doctor->consultation_fee;
		}
	}



	/*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/


	public function doctor()
	{
		return $this->belongsTo(Doctor::class);
	}

	public function consultations()
	{
		return $this->hasMany(Consultation::class);
	}


	/*
	|--------------------------------------------------------------------------
	| @Methods
	|--------------------------------------------------------------------------
	*/

	public function toSearchableArray()
    {
        return [
        
        ];
    }

	public static function store($request)
	{	
		ProcessSchedule::dispatch($request);

	}

	public static function storeSchedules($schedules, $date, $doctor)
	{

		foreach ($schedules as $key => $schedule) {
			$exists = $doctor->schedules()->whereDate('date', $date)->where(['start_time' => $schedule['start_time'], 'end_time' => $schedule['end_time'], 'type' => $schedule['type']])->first();
			if(!$exists) {
				$doctor->schedules()->create([
					'doctor_id' => $doctor->id,
					'type' => $schedule['type'],
					'date' => $date,
					'start_time' => $schedule['start_time'],
					'end_time' => $schedule['end_time'],
					'status' => $schedule['status'] ? true: false
				]);
			} else {
				$exists->update([
					'status' => $schedule['status'] ? true: false
				]);
			}
		}
	}

	public static function fetch($doctorID, $date)
	{
		$date = $date ? $date : Carbon::now();
		$schedules = Schedule::where(['doctor_id' => $doctorID])->whereDate('date', $date)->get();
		$schedules = collect($schedules)->map(function($schedule) {
						if(count($schedule->consultations)) {
							if($schedule->consultations()->whereIn('status', [0, 2, 3, 4])->count()) {
								return $schedule;
							}
						} else {
							return $schedule;
						}
					});
		return $schedules;
	}

	/**
	 * Format items
	 * 
	 * @param  array $items
	 */
	public static function formatItems($items)
	{
		$data = [];

		foreach ($items as $key => $item) {
			array_push($data, [
	            'id' => $item->id,
	            'date' => $item->date,
	            'start_time' => $item->start_time,
	            'end_time' => $item->end_time,
	            'type' => $item->type,
	            'status' => $item->status ? 'green': null,
        	]);
		}

		return $data;

	}


	/*
	|--------------------------------------------------------------------------
	| @Renders
	|--------------------------------------------------------------------------
	*/


	/*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/
}
