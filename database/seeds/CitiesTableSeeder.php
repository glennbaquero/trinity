<?php

namespace App\Seeders;

use App\Models\Cities\City;

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::truncate();

        foreach ($this->cities() as $city) {
            City::create($city);
        }
    }

    public function cities()
    {
    	return [
    		[
    			'province_id' => 1,
    			'name' => 'Angeles'
    		],
    		[
    			'province_id' => 1,
    			'name' => 'San Fernando'
    		],
    		[
    			'province_id' => 2,
    			'name' => 'Malolos'
    		],
    		[
    			'province_id' => 2,
    			'name' => 'Meycauayan'
    		],
    		[
    			'province_id' => 3,
    			'name' => 'Cabuyao'
    		],
    		[
    			'province_id' => 3,
    			'name' => 'Biñan'
    		],
    		[
    			'province_id' => 4,
    			'name' => 'Dasmariñas'
    		],
    		[
    			'province_id' => 4,
    			'name' => 'General Trias'
    		],
    		[
    			'province_id' => 5,
    			'name' => 'Legazpi'
    		],
    		[
    			'province_id' => 5,
    			'name' => 'Ligao'
    		],
    		[
    			'province_id' => 6,
    			'name' => 'Masbate'
    		],
    	];
    }
}
