<?php

use Illuminate\Database\Seeder;

use App\Models\ShippingMatrixes\ShippingMatrix;
use App\Models\Areas\Area;
class ShippingMatrixesSeeder extends Seeder
{
	private $shippings = [
		[
			'area_id' => 1,
			'free' => 0,
			'fee' => 200 
		],
		[
			'area_id' => 2,
			'free' => 0,
			'fee' =>  150
		],
		[
			'area_id' => 3,
			'free' => 0,
			'fee' =>  300
		],
		[
			'area_id' => 4,
			'free' => 1,
			'fee' =>  50,
			'quantity' => 1,
			'quantity_minimum' => 5,
			'price' => 1,
			'price_minimum' => 1500
		],
		[
			'area_id' => 5,
			'free' => 1,
			'fee' =>  50,
			'quantity' => 1,
			'quantity_minimum' => 3,
			'price' => 1,
			'price_minimum' => 1000
		],		
	];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		ShippingMatrix::truncate();

		foreach ($this->shippings as $key => $shipping) {
			ShippingMatrix::create($shipping);
		}
    }
}
