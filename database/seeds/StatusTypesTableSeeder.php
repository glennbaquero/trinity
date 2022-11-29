<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;

use App\Models\StatusTypes\StatusType;

class StatusTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
    	/** Clear table */
    	StatusType::truncate();

    	$this->populateSeeder();
    }

    public function populateSeeder()
    {

    	foreach ($this->generateData() as $key => $data) {
    		
    		/** Create status type */
    		StatusType::create($data);
    	}

    }	


    public function generateData()
    {


    	$types = [
    		[
    			'name' => 'Pending',
    			'order' => 0,
    			'bg_color' => '#0275d8',
    			'action_type' => StatusType::GO_NEXT_ACTION,
    		],

			[
    			'name' => 'Preparing',
    			'order' => 1,
    			'bg_color' => '#5bc0de',
                'action_type' => StatusType::GO_NEXT_ACTION,
    		],

			[
    			'name' => 'Ready To Ship',
    			'order' => 2,
    			'bg_color' => '#0275d8',
                'action_type' => StatusType::GO_NEXT_ACTION,
    		],

			[
    			'name' => 'Delivery',
    			'order' => 3,
    			'bg_color' => '#0275d8',
                'action_type' => StatusType::GO_NEXT_ACTION,
    		],

			[
    			'name' => 'Returned',
    			'order' => 4,
    			'bg_color' => '#f0ad4e',
                'action_type' => StatusType::CANCELLED,
    		],

			[
    			'name' => 'Cancelled',
    			'order' => 5,
    			'bg_color' => '#d9534f',
                'action_type' => StatusType::CANCELLED,
    		],

			[
    			'name' => 'Completed',
    			'order' => 6,
    			'bg_color' => '#5cb85c',
                'action_type' => StatusType::COMPLETED,
    		],

    	];

    	return $types;

    }    

}
