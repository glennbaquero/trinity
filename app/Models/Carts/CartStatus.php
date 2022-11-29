<?php

namespace App\Models\Carts;

use App\Models\Carts\Cart;
use Illuminate\Database\Eloquent\Model;

class CartStatus extends Model
{
    
    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	public function cart()
	{
		return $this->belongsTo(Cart::class);
	}

}
