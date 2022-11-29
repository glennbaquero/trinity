<?php

namespace App\Seeders;

use App\Models\Carts\Cart;

use Illuminate\Database\Seeder;

class CartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Cart::truncate();
    	
        $ids = [1, 2, 3];

        foreach ($ids as $id) {
        	Cart::create(['user_id' => $id]);
        }
    }
}
