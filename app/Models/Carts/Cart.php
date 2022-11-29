<?php

namespace App\Models\Carts;

use App\Models\Users\User;
use App\Models\Carts\CartItem;
use App\Models\Carts\CartStatus;
use App\Models\Invoices\Invoice;
use App\Models\Invoices\InvoiceItem;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{

	protected $guarded = [];

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function user()
	{
		return $this->belongsTo(User::class)->withTrashed();
	}
    
	public function cartItems()
	{
		return $this->hasMany(CartItem::class);
	}

	public function cartStatuses()
	{
		return $this->hasMany(CartStatus::class);
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
	}


	/*
	|--------------------------------------------------------------------------
	| @Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Add product to cart
	 * 
	 * @param int $id
	 * @param int $quantity
	 */
	public static function addToCart($id, $quantity, $doctor = null)
	{
		$user = request()->user();
    	$cart = $user->getOrCreateCart();

    	// check if the product is exist in cart items of a user
    	$exists = $cart->cartItems->where('product_id', $id)->first();

    	if (!$exists) {
    		$newItem = $cart->cartItems()->create([
    			'product_id' => $id,
    			'quantity' => $quantity,
                'doctor_id' => $doctor ? $doctor : Cart::checkLinkedDoctor($id),
                'consultation_id' => Cart::checkLinkedConsultation($id),
                'prescription_path' => Cart::checkLinkedPrescription($id)                 
    		]);
    	} else {
    		$exists->update(['doctor_id' => $doctor ? $doctor : Cart::checkLinkedDoctor($id)]);
    		$exists->increment('quantity', $quantity);
    	}

    	return [
    		'cart' => $cart,
    		'exists' => !$exists ? $newItem : false,
    	];

        // if (!$request->user()->products()->find($request->parent_id)) {
        //     $request->user()->products()->attach($request->parent_id);
        // }
	}

	/**
	 * Check linked doctor
	 * 
	 * @param  int $productID
	 */
	public static function checkLinkedDoctor($productID)
	{
		$user = request()->user();
		$invoices = $user->invoices()->pluck('id')->toArray();

		$invoiceItem = InvoiceItem::latest()
			->whereIn('invoice_id', $invoices)
			->where(['product_id' => $productID, ['doctor_id', '!=', null] ])->first();

		if($invoiceItem) {
			return $invoiceItem->doctor_id;
		}
	}

	/**
	 * Check linked consultation
	 * 
	 * @param  int $productID
	 */
	public static function checkLinkedConsultation($productID)
	{
		$user = request()->user();
		$invoices = $user->invoices()->pluck('id')->toArray();

		$invoiceItem = InvoiceItem::latest()
			->whereIn('invoice_id', $invoices)
			->where(['product_id' => $productID, ['consultation_id', '!=', null] ])->first();

		if($invoiceItem) {
			return $invoiceItem->consultation_id;
		}
	}

	/**
	 * Check linked prescription
	 * 
	 * @param  int $productID
	 */
	public static function checkLinkedPrescription($productID)
	{
		$user = request()->user();
		$invoices = $user->invoices()->pluck('id')->toArray();

		$invoiceItem = InvoiceItem::latest()
			->whereIn('invoice_id', $invoices)
			->where(['product_id' => $productID, ['prescription_path', '!=', null] ])->first();

		if($invoiceItem) {
			return $invoiceItem->prescription_path;
		}
	}

}
