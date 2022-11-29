<?php

namespace App\Models\Vouchers;

use App\Extendables\BaseModel as Model;	

use App\Models\Users\User;
use App\Models\Vouchers\UsedVoucher;

use Carbon\Carbon;

class UserVoucher extends Model
{

    /*
	|--------------------------------------------------------------------------
	| Constants
	|--------------------------------------------------------------------------
	*/

	const AMOUNT = 0;
	const PERCENTAGE = 1;

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/	
	public function voucher() 
	{
		return $this->belongsTo(Voucher::class)->withTrashed();
	}

	public function user() 
	{
		return $this->belongsTo(User::class)->withTrashed();
	}

	public function usedVouchers() 
	{
		return $this->hasMany(UsedVoucher::class);
	}


	/*
	|--------------------------------------------------------------------------
	| Checkers
	|--------------------------------------------------------------------------
	*/

	/**
	 * 
	 * @return Boolean Check if the voucher is expired
	 */
	public function checkExpirationStatus()
	{	
		if($this->expired_at < Carbon::now()) {
			return true;
		}

		return false;
	}

	/**
	 * validate the status of the voucher
	 * @return json 
	 */
	public function checkVoucherStatus() 
	{	
		if($this) {
			if($this->checkExpirationStatus()) {
		    	return ([
		    		'status' => 'expired',
		            'message' => 'Voucher code is expired',
		        ]);
			}

			if($this->usedVouchers()->count() >= $this->max_usage ) {
		    	return ([
		    		'status' => 'exceeded',
		            'message' => 'Voucher maximum usage has been reach',
		        ]);
			}

	    	return response()->json([
	            'status' => 'verified',
	            'message' => 'Voucher successfully applied',
	            'voucher' => $this,
	        ]);
		}
	}

    /*
	|--------------------------------------------------------------------------
	| Renders
	|--------------------------------------------------------------------------
	*/

	public function renderName() 
	{
	    return $this->name;
	}

	public function renderType() 
	{
	    switch ($this->type) {
	    	case 0:
	    		return ['value' => static::AMOUNT, 'name' => 'Amount', 'class' => 'success'];
	    		break;
	    	case 1:
	    		return ['value' => static::PERCENTAGE , 'name' => 'Percentage', 'class' => 'info'];
	    		break;
	    }

	}

}
