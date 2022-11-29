<?php

namespace App\Http\Controllers\API\Care\Carts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\API\Care\Carts\CartFetchController;

use App\Models\Carts\Cart;
use App\Models\Carts\CartItem;
use App\Models\Products\Product;
use App\Models\Prescriptions\Prescription;

use Illuminate\Validation\ValidationException;

use DB;

class CartController extends Controller
{

    /**
     * View cart
     *
     * @param Illuminate\Http\Request
     */

    public function viewCart(Request $request)
    {

        $cartItems = $request->user()->getCart() ?? [];
        $sub_total = 0;

        foreach ($cartItems as $cartItem) {
            $price = isset($cartItem->product->price) ? $cartItem->product->price : 0;
            $sub_total += $price * $cartItem->quantity;
        }

        return response()->json([
            'items' => $cartItems,
            'sub_total' => $sub_total
        ]);
    }

	/**
	 * Create new cart for user if no cart is in pending
	 *
	 * @param Illuminate\Http\Request
	 */

    public function addToCart(Request $request)
    {

    	DB::beginTransaction();

            $item = Cart::addToCart($request->product_id, $request->quantity);

    	DB::commit();

    	return response()->json([
    		'message' => 'Successfully added to cart',
    		'response' => 200,
            'new_item' => $item['exists'],
            'total_cart_items' => $item['cart']->cartItems()->count()
    	]);
    }

    /**
     * Update cart item if the user update the quantity of the product
     *
     * @param Illuminate\Http\Request
     */

    public function updateCartItem(Request $request)
    {
		$item = CartItem::find($request->id);
        $max = $request->type === 'increment' && $item->quantity >= $item->product->inventory->stocks;

        if ($item->quantity >= $item->product->inventory->stocks && $request->type == 'increment') {
            return;
        }

        if($request->quantity === 0) {
            DB::beginTransaction();
                $item->delete();
            DB::commit();
        } else {
            DB::beginTransaction();
                $item->quantity = $request->quantity;
                $item->save();
            DB::commit();            
        }



    	return response()->json([
    		'message' => 'Cart has been successfully updated',
    		'response' => 200
    	]);
    }

    /**
     * Removing of item in cart
     *
     * @param Illuminate\Http\Request
     */
    public function deleteCartItem(Request $request)
    {
    	DB::beginTransaction();
    		CartItem::destroy($request->id);
    	DB::commit();

    	return response()->json([
    		'message' => 'Item deleted',
    		'response' => 200
    	]);
    }

    /**
     * Validate if a doctor with requested QR exists
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function validateQr(Request $request)
    {
        $qr_id = Product::find($request->product_id)->specialization->doctors
                    ->where('qr_id', $request->qr_id)
                    ->first();

        if (!$request->qr_id) {
            throw ValidationException::withMessages([
                'qr_id' => ['Please enter the QR ID']
            ]);
        }

        if (!$qr_id) {
            throw ValidationException::withMessages([
                'qr_id' => ['Entered QR ID does not match the doctor\'s QR ID']
            ]);
        }

        return response()->json([
            'message' => 'Success'
        ]);
    }


    /**
     * Linking of MD to specific cart item
     * 
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function linkedMDUpdate(Request $request)
    {
        $cartItem = CartItem::find($request->cart_item_id);

        /** Start transaction */
        DB::beginTransaction();

            $cartItem->linkMD($request->doctor_id);            

        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => 'Cart item has been successfully updated.',
        ]);

    }

    /**
     * Upload prescription to specific cart item
     * 
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function uploadPrescription(Request $request)
    {
        $cartItem = CartItem::find($request->cart_item_id);

        /** Start transaction */
        DB::beginTransaction();

            $cartItem->uploadPrescription($request);            

        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => 'Prescription has been successfully submitted.',
        ]);

    }

}
