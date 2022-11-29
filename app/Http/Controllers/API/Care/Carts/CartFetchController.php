<?php

namespace App\Http\Controllers\API\Care\Carts;

use Illuminate\Http\Request;
use App\Http\Controllers\FetchController;

use App\Models\Carts\Cart;

class CartFetchController extends FetchController
{
    public $request;

    public function __construct($request) 
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
        $this->class = new Cart;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        /**
         * Queries
         * 
         */

        return $query;
    }

    /**
     * Custom formatting of data
     * 
     * @param Illuminate\Support\Collection $items
     * @return array $result
     */
    public function formatData($cart)
    {
        $result = [];
        $cartItems = $this->request->user()->getCart();
        
        if ($cartItems) {
            foreach($cartItems as $item) {
                if($item->product) {
                    array_push($result, [
                        'cart_id' => $item->cart_id,
                        'id' => $item->id,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity,
                        'product_name' => $item->product->name,
                        'product_price' => $item->product->price,
                        'image_path' => url($item->product->renderImagePath()),
                        'total_per_item' => number_format($item->product->price * $item->quantity, 2),
                        'max' => $item->quantity >= $item->product->inventory->stocks
                    ]); 
                }
            }
        }

        return $result;
    }
}
