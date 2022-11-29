<?php

use Illuminate\Database\Seeder;

use App\Models\Products\Product;

class ProductSeederFromUATtoLive extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $row = 0;
        
        if(($handle = fopen('database/csv/products.csv', "r")) !== FALSE){
        	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        		if($row != 0) {
        			$this->command->info('writing row ' . $row . ' ' . $data[2]);

        			// $item = new Product();
        			Product::create([
        				'specialization_id' => $data[0],
        				'brand_name' => $data[1],
        				'name' => $data[2],
        				'sku' => $data[3],
        				'product_size' => $data[4],
        				'image_path' => $data[5],
        				'prescriptionable' => $data[6],
        				'is_other_brand' => $data[7],
        				'price' => $data[8],
        				'client_points' => $data[9],
        				'doctor_points' => $data[10],
        				'ingredients' => $data[11],
        				'nutritional_facts' => $data[12],
        				'directions' => $data[13],
        				'description' => $data[14],
        				'is_free_product' => $data[15],
        			]);

        			// $item->save();
        		}

                $row++;

            }
            fclose($handle);
        }
    }
}
