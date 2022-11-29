<?php

namespace App\Seeders;

use App\Models\Carts\CartItem;

use Illuminate\Database\Seeder;

class CartItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CartItem::truncate();

        foreach ($this->items() as $item) {
        	CartItem::create($item);
        }
    }

    public function items()
    {
    	return [
    		[
	    		'cart_id' => 1,
	    		'product_id' => 1,
	    		'quantity' => 3,
	    	],
	    	[
	    		'cart_id' => 1,
	    		'product_id' => 2,
	    		'quantity' => 5,
	    	],
	    	[
	    		'cart_id' => 2,
	    		'product_id' => 1,
	    		'quantity' => 6,
	    	],
	    	[
	    		'cart_id' => 2,
	    		'product_id' => 2,
	    		'quantity' => 2,
	    	],
	    	[
	    		'cart_id' => 2,
	    		'product_id' => 2,
	    		'quantity' => 4,
	    	],
	    	[
	    		'cart_id' => 3,
	    		'product_id' => 3,
	    		'quantity' => 2,
	    	],
    	];
    }
}
