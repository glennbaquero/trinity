<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Addresses\Address;

class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Address::truncate();
    	
        foreach ($this->addresses() as $address) {
        	Address::create($address);
        }
    }

    public function addresses()
    {
    	return [
    		[
    			'user_id' => 1,
    			'region_id' => 1,
    			'province_id' => 1,
                'city_id' => 1,
    			'unit' => '123',
    			'building' => 'Symphony',
    			'street' => 'Sesame',
    			'zip' => '1234',
    			'landmark' => 'Landmark',
    			'default' => true
    		],
    		[
    			'user_id' => 1,
    			'region_id' => 2,
    			'province_id' => 3,
                'city_id' => 6,
    			'unit' => '321',
    			'building' => 'Vista',
    			'street' => 'Masagana',
    			'zip' => '4321',
    			'landmark' => 'Landmark',
    			'default' => false
    		]
    	];
    }
}
