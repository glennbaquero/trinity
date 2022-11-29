<?php

use Illuminate\Database\Seeder;

use App\Models\Inventories\Inventory;

class InventoryUATToLiveTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = 0;
        
        if(($handle = fopen('database/csv/inventories.csv', "r")) !== FALSE){
        	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        		if($row != 0) {
        			$this->command->info('writing row ' . $row . ' ' . $data[0]);
        			$inventory = Inventory::where('product_id', $data[0])->first();

        			if($inventory) {
        				$inventory->update([
        					'stocks' => $data[1]
        				]);	
        			} else {
        				Inventory::create([
        					'product_id' => $data[0],
        					'stocks' => $data[1],
        				]);
        			}
        		}

                $row++;

            }
            fclose($handle);
        }
    }
}
