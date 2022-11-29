<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Users\Doctor;

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DoctorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Doctor::truncate();

        foreach ($this->doctors() as $doctor) {
        	Doctor::create($doctor);
        }
    }

    public function doctors()
    {
    	return [
    		[
    			'medical_representative_id' => 1,
    			'specialization_id' => 2,
    			'email' => 'tristan.gua@trinity.com',
    			'password' => Hash::make('password'),
    			'first_name' => 'Tristan',
    			'last_name' => 'Gua',
    			'mobile_number' => '09123456789',
                'consultation_fee' => 100
    		],
    		[
    			'medical_representative_id' => 2,
    			'specialization_id' => 1,
    			'email' => 'john.doe@trinity.com',
    			'password' => Hash::make('password'),
    			'first_name' => 'John',
    			'last_name' => 'Doe',
    			'mobile_number' => '09123456789',
                'consultation_fee' => 100
    		],
    		[
    			'medical_representative_id' => 3,
    			'specialization_id' => 3,
    			'email' => 'doe.john@trinity.com',
    			'password' => Hash::make('password'),
    			'first_name' => 'Doctor',
    			'last_name' => 'Doe',
    			'mobile_number' => '09123456789',
                'consultation_fee' => 100
    		],
    	];
    }
}
