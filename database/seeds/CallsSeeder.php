<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Calls\Call;

class CallsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Call::truncate();

        foreach ($this->calls() as $call) {
        	Call::create($call);
        }
    }

    public function calls()
    {
    	return [
    		[
    			'medical_representative_id' => 1,
    			'doctor_id' => 1,
    			'clinic' => 'Clinic 1',
    			'scheduled_date' => '2019-09-20',
    			'scheduled_time' => '08:00:00',
    			'agenda' => 'Agenda 1'
    		],
    		[
    			'medical_representative_id' => 1,
    			'doctor_id' => 2,
    			'clinic' => 'Clinic 2',
    			'scheduled_date' => '2019-09-20',
    			'scheduled_time' => '08:00:00',
    			'agenda' => 'Agenda 2'
    		],
    		[
    			'medical_representative_id' => 2,
    			'doctor_id' => 2,
    			'clinic' => 'Clinic 3',
    			'scheduled_date' => '2019-09-20',
    			'scheduled_time' => '08:00:00',
    			'agenda' => 'Agenda 3'
    		],
    		[
    			'medical_representative_id' => 3,
    			'doctor_id' => 1,
    			'clinic' => 'Clinic 4',
    			'scheduled_date' => '2019-09-20',
    			'scheduled_time' => '08:00:00',
    			'agenda' => 'Agenda 4'
    		],
    	];
    }
}
