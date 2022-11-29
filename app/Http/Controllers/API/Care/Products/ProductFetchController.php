<?php

namespace App\Http\Controllers\API\Care\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\FetchController;

use App\Models\Users\User;
use App\Models\Specializations\Specialization;
use App\Models\Products\Product;

class ProductFetchController extends FetchController
{
    public $request;

    protected $total = 10;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
    * Set object class of fetched data
    *
    * @return void
    */
    public function setObjectClass()
    {
        $this->class = new Specialization;
    }

    /**
    * Custom filtering of query
    *
    * @param Illuminate\Support\Facades\DB $query
    * @return Illuminate\Support\Facades\DB $query
    */
    public function filterQuery($query)
    {
        $this->total = 10;

        /**
        * Queries
        *
        */
        if ($this->request->filled('specializations')) {
            $query = $query->whereIn('id', $this->request->specializations);
        }

        return $query;
    }

    /**
    * Custom formatting of data
    *
    * @param Illuminate\Support\Collection $items
    * @return array $result
    */
    public function formatData($items)
    {
        $products = [];

            $items = new Product;

            if($this->request->filled('product_name')) {
                $items = $items->where('name', 'like', '%'.$this->request->product_name.'%')
                        ->orWhere('generic_name', 'like', '%'.$this->request->product_name.'%')
                        ->orWhere('brand_name', 'like', '%'.$this->request->product_name.'%');
            }

            if($this->request->filled('regulatory_classification')) {

                $regulatory = $this->request->regulatory_classification;

                switch ($regulatory) {
                    case 3:
                        $items = $items->where('prescriptionable', 0);
                        break;
                    case 2:
                        $items = $items->where('prescriptionable', 1);
                        break;
                    case 1:
                        $items = $items;
                        break;  
                }
            }

            if($this->request->filled('sort_by')) {
                $sort_by = $this->request->sort_by;
                switch ($sort_by) {
                    case 2:
                        $items = $items->orderBy('price','desc');
                        break;
                    case 3:
                        $items = $items->orderBy('price','asc');
                        break;
                    case 4:
                        $items = $items->orderBy('name','asc');
                        break;
                    case 5:
                        $items = $items->orderBy('name','desc');
                        break;
                }
            }

            if($this->request->range) {
                $range = $this->request->range;
                $items = $items->whereBetween('price',$range);
            }

            if($this->request->filled('specialization')) {
                if($this->request->specialization != 0) {
                    $id = $this->request->specialization;
                    $items = $items->whereHas('specializations', function ($query) use($id) {
                        return $query->where('id',$id);
                    }); 
                }
            }

            $items = $items->paginate(10); 
            
            foreach ($items as $product) {
                array_push($products,[
                    'id' => $product->id,
                    'parent_id' => $product->parent_id,
                    'specialization' => $product->specialization,
                    'name' => $product->name,
                    'size' => $product->product_size,
                    'description' => $product->description,
                    'ingredients' => $product->ingredients,
                    'nutritional_facts' => $product->nutritional_facts,
                    'directions' => $product->directions,
                    'client_points' => $product->renderClientPoints(),
                    'prescriptionable' => $product->prescriptionable,
                    'price' => $product->price,
                    'stocks' => $product->inventory ? $product->inventory->stocks : 0,
                    'bought' => $product->renderBoughtStatus(request()->user()),
                    'full_image' => url($product->renderImagePath()),
                    'is_free_product' => $product->is_free_product
                ]);
            }

        return $products;
    }

    /**
    * Fetch product filters
    * 
    * @param  Request $request
    */
    public function fetchFilter(Request $request)
    {
    
        $highest_price = Product::max('price');
        $lowest_price = Product::min('price');

        $price = ['lowest' => $lowest_price,'highest' => $highest_price];

        $specialization = Specialization::get(['id','name']);
        $specialization->push(['id' => 0, 'name' => 'All']);

        return response()->json([
            'specializations' => $specialization,
            'prices' => $price,
        ]);
    }
}
