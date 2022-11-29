<?php

namespace App\Seeders;

use App\Models\Provinces\Province;

use Illuminate\Database\Seeder;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Province::truncate();
        
        foreach ($this->provinces() as $province) {
        	Province::create($province);
        }
    }

    public function provinces()
    {
    	return [
    		[
    			'region_id' => 1,
    			'name' => 'Pampanga'
    		],
    		[
    			'region_id' => 1,
    			'name' => 'Bulacan'
    		],
    		[
    			'region_id' => 2,
    			'name' => 'Laguna'
    		],
    		[
    			'region_id' => 2,
    			'name' => 'Cavite'
    		],
    		[
    			'region_id' => 3,
    			'name' => 'Albay'
    		],
    		[
    			'region_id' => 3,
    			'name' => 'Masbate'
    		],
    	];
    }
}
