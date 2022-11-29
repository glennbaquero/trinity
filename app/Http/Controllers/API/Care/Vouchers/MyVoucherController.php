<?php

namespace App\Http\Controllers\API\Care\Vouchers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vouchers\Voucher;
use App\Models\Vouchers\UserVoucher;

class MyVoucherController extends Controller
{
	/**
	 * Fetch user pending & active vouchers
	 * 
	 * @param  Request $request 
	 */
	public function fetch(Request $request) 
	{
		$user = $request->user();
		$pending = $user->getPendingRequestVouchers();
		$active = $user->getActiveVouchers();

		return response()->json([
			'pending' => $pending,
			'active' => $active,
		]);
	}

	/**
	 * Redeem Voucher
	 * 
	 * @param  Request $request (code)
	 */
	public function redeem(Request $request) 
	{
		$user = $request->user();
		$code = $request->code;

		if($code) {
			$voucher = $user->myVouchers()->where('code', $code)->first();
			
			if(!$voucher) {
				$voucher = Voucher::where(['code' => $code, 'voucher_type' => Voucher::GLOBAL_USAGE])->first();
			}

			if($voucher) {

				$voucher = $voucher->checkVoucherStatus();

			} else {

				return response()->json([
					'status' => 'not_found',
					'message' => 'Voucher not found'
				]);
			}
			
			return $voucher;
		}
	}

}