<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Specializations\Specialization;

class SpecializationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
    	/** Clear table */
    	Specialization::truncate();

    	$this->populateSeeder();
    }

    public function populateSeeder()
    {

    	foreach ($this->generateData() as $key => $data) {

    		/** Store specialization */
    		Specialization::create($data);
    	
    	}

    }

    public function generateData()
    {
    	$specializations = [
    		[
	    		'id' => 1,
	    		'name' => 'Family Physician',
    		],
    		[
	    		'id' => 2,
	    		'name' => 'Internal Medicine Physician',
    		],
    		[
	    		'id' => 3,
	    		'name' => 'Pediatrician',
    		],
			[
	    		'id' => 4,
	    		'name' => 'Obstetrician/Gynecologist (OB/GYN)',
    		],
			[
	    		'id' => 5,
	    		'name' => 'Surgeon',
    		],
			[
	    		'id' => 6,
	    		'name' => 'Psychiatrist',
    		],
			[
	    		'id' => 7,
	    		'name' => 'Cardiologist',
    		],    		
			[
	    		'id' => 8,
	    		'name' => 'Dermatologist',
    		],
    		[
	    		'id' => 9,
	    		'name' => 'Endocrinologist',
    		],
    		[
	    		'id' => 10,
	    		'name' => 'Gastroenterologist',
    		],
    		[
	    		'id' => 11,
	    		'name' => 'Infectious Disease Physician',
    		],
    		[
	    		'id' => 12,
	    		'name' => 'Nephrologist',
    		],
    		[
	    		'id' => 13,
	    		'name' => 'Ophthalmologist',
    		],
    		[
	    		'id' => 14,
	    		'name' => 'Pulmonologist',
    		],
    		[
	    		'id' => 15,
	    		'name' => 'Neurologist',
    		],
    		[
	    		'id' => 16,
	    		'name' => 'Physician Executive',
    		],
    		[
	    		'id' => 17,
	    		'name' => 'Radiologist',
    		],
    		[
	    		'id' => 18,
	    		'name' => 'Anesthesiologist',
    		],
			[
	    		'id' => 19,
	    		'name' => 'Oncologist',
    		],

    	];

    	return $specializations;
    }
}
