<?php

namespace App\Http\Controllers\API\Care\Resources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ShippingMatrixes\ShippingMatrix;

class ShippingMatrixController extends Controller
{

	public function fetch()
	{

		$shippingMatrixes = ShippingMatrix::has('area')->get();
		
		return response()->json([
			'shippingMatrixes' => $shippingMatrixes,
		]);

	}
}