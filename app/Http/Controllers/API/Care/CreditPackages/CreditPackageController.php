<?php

namespace App\Http\Controllers\API\Care\CreditPackages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\CreditPackages\CreditPackage;
use App\Models\CreditInvoices\CreditInvoice;

class CreditPackageController extends Controller
{

	/**
	 * Fetch history
	 * 
	 * @return Illuminate\Http\Response
	 */
	public function history()
	{
		$user = request()->user();

		$transactions = CreditInvoice::where(['user_id' => $user->id])->latest()->get();
		$credits = $user->countCredits();

		return response()->json([
			'transactions' => $transactions,
			'credits' => $credits
		]);

	}

	/**
	 * Fetch resources from storage
	 * 
	 * @return Illuminate\Http\Response
	 */
	public function fetch()
	{
		$packages = CreditPackage::where('status', CreditPackage::ENABLED)->get();

		return response()->json([
			'packages' => $packages
		]);
	}

}
