<?php

namespace App\Seeders;

use App\Models\Prescriptions\Prescription;

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class PrescriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Prescription::truncate();

        foreach ($this->prescriptions() as $prescription) {
        	Prescription::create($prescription);
        }
    }

    public function prescriptions()
    {
    	$faker = Faker::create();

    	return [
    		[
    			'user_id' => 1,
                'product_id' => 1,
    			'image_path' => "prescriptions/{$faker->md5}.png",
    			'approved' => true
    		],
    		[
    			'user_id' => 1,
                'product_id' => 2,
    			'image_path' => "prescriptions/{$faker->md5}.png",
    			'approved' => false
    		],
    		[
    			'user_id' => 2,
                'product_id' => 3,
    			'image_path' => "prescriptions/{$faker->md5}.png",
    			'approved' => true
    		],
    		[
    			'user_id' => 3,
                'product_id' => 4,
    			'image_path' => "prescriptions/{$faker->md5}.png",
    		],
    		[
    			'user_id' => 4,
                'product_id' => 5,
    			'image_path' => "prescriptions/{$faker->md5}.png",
    		],
    	];
    }
}
