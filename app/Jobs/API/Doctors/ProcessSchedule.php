<?php

namespace App\Jobs\API\Doctors;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Schedules\Schedule;
use App\Models\Users\Doctor;
use Carbon\Carbon;

class ProcessSchedule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request->toArray();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $schedules = $this->request['schedules'];
        $date = $this->request['date'];
        $type = $this->request['type'];
        $doctor = Doctor::find($this->request['doctor_id']);

        $start = Carbon::parse($date);
        if($type == 'every_week') {
            $dayOfWeek = Carbon::parse($date)->dayOfWeek;           
            $end = Carbon::parse($date)->addYear(1);
            for ($loopDate = $start; $loopDate->lt($end); $loopDate->addWeek()) {
                if($dayOfWeek == $loopDate->dayOfWeek) {
                    Schedule::storeSchedules($schedules, $loopDate, $doctor);
                }
            }

        } else if ($type == 'every_month') {
            $day = Carbon::parse($date)->day;
            $end = Carbon::parse($date)->addYear(2);
            for ($loopDate = $start; $loopDate->lt($end); $loopDate->addMonth()) {
                if($day == $loopDate->day) {
                    Schedule::storeSchedules($schedules, $loopDate, $doctor);
                }
            }
        } else if ($type == 'every_year') {
            $dayOfYear = Carbon::parse($date)->dayOfYear;
            $end = Carbon::parse($date)->addYear(10);
            for ($loopDate = $start; $loopDate->lt($end); $loopDate->addYear()) {
                Schedule::storeSchedules($schedules, $loopDate, $doctor);
            }
        } else if ($type == 'every_day') {
            $end = Carbon::parse($date)->addYear(1);
            $day = Carbon::parse($date)->day;
            for ($loopDate = $start; $loopDate->lt($end); $loopDate->addDay()) {
                Schedule::storeSchedules($schedules, $loopDate, $doctor);
            }

        } else if ($type == 'no_repeat') {
        
            Schedule::storeSchedules($schedules, $start, $doctor);           
        
        } else {
            $start = Carbon::now();
            $end = Carbon::parse($date);            
            $recurrence_type = $this->request['recurrence_type'];
            $selected_days = $this->request['selected_days'];
            if($recurrence_type == 'never') {
                $end = Carbon::parse($date)->addYear(2);
            }

            for ($loopDate = $start; $loopDate->lt($end); $loopDate->addDay()) {
                if(in_array($loopDate->dayOfWeek, $selected_days)) {
                    Schedule::storeSchedules($schedules, $loopDate, $doctor);
                }
            }

        }


    }


}
