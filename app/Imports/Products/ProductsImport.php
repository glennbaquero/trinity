<?php

namespace App\Imports\Products;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\ValidationException;

use Storage;
use App\Models\Products\Product;
use App\Models\Inventories\Inventory;
use App\Models\Specializations\Specialization;

class ProductsImport implements ToCollection, WithHeadingRow
{

	protected $request;

	public function __construct($request)
	{
	    $this->request = $request;
	}


    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        /** Clear products */
        // Product::truncate();

        /** Clear inventories */
        // Inventory::truncate();

        

    	foreach ($rows as $key => $row) {
    		
    		$specialization = Specialization::where('name', $row['specialization'])->first();
			$image = '';

    		if ($this->request->hasFile('images')) {
    			if($this->request->file('images')[$key]->getClientOriginalName() === $row['image_path']) {
			        $image = $this->request->file('images')[$key]->store('product-images', 'public');
    			} else {
    				throw ValidationException::withMessages([
		                'message' => ['No image found for product '. $row['name']]
		            ]);
    			}
    		}

    		if($specialization) {
	    		$product = Product::updateOrCreate([
	    			'specialization_id' => $specialization->id,
		            'name' => $row['name'],
		            'brand_name' => $row['name'],
		            'sku' => $row['sku'],
		            'image_path' => $image ? 'public/'.$image : '',
		            'product_size' => $row['size'],
		            'price' => $row['price'],
		            'client_points' => $row['client_points'],
		            'doctor_points' => $row['doctor_points'],
		            'secretary_points' => isset($row['secretary_points']) ? $row['secretary_points'] : 0,
		            'prescriptionable' => $row['has_prescription'],
		            'is_other_brand' => $row['other_brand'],
		            'ingredients' => $row['ingredients'],
		            'nutritional_facts' => $row['nutritional_facts'],
		            'directions' => $row['directions'],
		            'description' => $row['description']
	    		]);

	            /** Create initial stocks */
	            if(!$product->inventory) {
		    		$product->inventory()->create([ 'stocks' => $row['initial_stocks'] ]);	            
	            }
    		}
    	}
    }
}
