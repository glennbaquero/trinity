<?php

namespace App\Models\Carts;

use App\Models\Carts\CartItem;
use Illuminate\Database\Eloquent\Model;

class ItemPrescription extends Model
{
    
    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function cartItem()
	{
		return $this->belongsTo(CartItem::class);
	}

}
