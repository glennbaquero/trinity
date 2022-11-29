<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Users\MedicalRepresentative;

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MedicalRepresentativesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MedicalRepresentative::truncate();

        foreach ($this->medReps() as $medRep) {
        	MedicalRepresentative::create($medRep);
        }
    }

    public function medReps()
    {
    	return [
    		[
    			'region_id' => 1,
    			'email' => 'john.doe@trinity.com',
    			'password' => Hash::make('password'),
    			'fullname' => 'John Doe',
    			'mobile' => '09123456789',
    			'email_verified_at' => Carbon::now()
    		],
    		[
    			'region_id' => 2,
    			'email' => 'alexa.monroe@trinity.com',
    			'password' => Hash::make('password'),
    			'fullname' => 'Alexa Monroe',
    			'mobile' => '09123456789',
    			'email_verified_at' => Carbon::now()
    		],
    		[
    			'region_id' => 3,
    			'email' => 'tony.parker@trinity.com',
    			'password' => Hash::make('password'),
    			'fullname' => 'Tony Parker',
    			'mobile' => '09123456789',
    			'email_verified_at' => Carbon::now()
    		],
    	];
    }
}
