<?php

namespace App\Models\Vouchers;

use App\Extendables\BaseModel as Model;
use App\Helpers\StringHelpers;

use App\Models\Vouchers\UsedVoucher;

use Carbon\Carbon;

class Voucher extends Model
{	

    /*
	|--------------------------------------------------------------------------
	| Constants
	|--------------------------------------------------------------------------
	*/
	const ALL = 0;
	const PATIENT = 1;
	const SECRETARY = 2;

	const AMOUNT = 0;
	const PERCENTAGE = 1;
    
	const REDEEMABLE = 0;
	const GLOBAL_USAGE = 1;

    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/
	
	public function userVouchers() 
	{
		return $this->hasMany(UserVoucher::class);
	}

	public function usedVouchers() 
	{
		return $this->hasMany(UsedVoucher::class);
	}

    /*
	|--------------------------------------------------------------------------
	| Methods
	|--------------------------------------------------------------------------
	*/
	public static function store($request, $item = null, $columns = ['voucher_type', 'name', 'code', 'type', 'discount', 'user_type', 'valid_days' , 'max_usage'])
    {	
    
        $vars = $request->only($columns);
       
        if (!$item) {
			$item = static::create($vars);
        } else {
            $item->update($vars);
        }
        
        return $item;
	}

    /*
	|--------------------------------------------------------------------------
	| Renders
	|--------------------------------------------------------------------------
	*/
	public function renderArchiveUrl($prefix = 'admin') 
	{
	    return route(StringHelpers::addRoutePrefix($prefix) . 'vouchers.archive', $this->id);
	}

	public function renderRestoreUrl($prefix = 'admin') 
	{
	    return route(StringHelpers::addRoutePrefix($prefix) . 'vouchers.restore', $this->id);
	}

	public function renderShowUrl($prefix = 'admin') 
	{
	    return route(StringHelpers::addRoutePrefix($prefix) . 'vouchers.show', $this->id);
	}

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

	public static function renderVoucherTypes()
	{
		return [
	    	['value' => static::REDEEMABLE , 'name' => 'For Redeem'],
	    	['value' => static::GLOBAL_USAGE , 'name' => 'Global Usage']
	    ];
	}

	public function renderVoucherType() 
	{
	    switch ($this->voucher_type) {
	    	case 0:
	    		return ['value' => static::REDEEMABLE, 'name' => 'For Redeem', 'class' => 'success'];
	    		break;
	    	case 1:
	    		return ['value' => static::GLOBAL_USAGE, 'name' => 'Global Usage', 'class' => 'info'];
	    		break;
	    }
	}

	public static function renderTypes() {
	    return [
	    	['value' => static::AMOUNT , 'name' => 'Amount'],
	    	['value' => static::PERCENTAGE , 'name' => 'Percentage']
	    ];
	}

	public function renderUserType() 
	{
	    switch ($this->user_type) {
	    	case 0:
	    		return ['value' => static::ALL, 'name' => 'All', 'class' => 'success'];
	    		break;
	    	case 1:
	    		return ['value' => static::PATIENT , 'name' => 'Patient', 'class' => 'info'];
	    		break;
    		case 2:
	    		return ['value' => static::SECRETARY , 'name' => 'Secretary', 'class' => 'warning'];
	    		break;
	    }
	}

	public static function renderUserTypes() 
	{
	    return [
	    	['value' => static::ALL , 'name' => 'All'],
	    	['value' => static::PATIENT , 'name' => 'Patient'],
	    	['value' => static::SECRETARY , 'name' => 'Secretary']
	    ];
	}

    /*
	|--------------------------------------------------------------------------
	| Checkers
	|--------------------------------------------------------------------------
	*/


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

	/**
	 * Check if the voucher is expired
	 * 
	 * @return Boolean 
	 */
	public function checkExpirationStatus()
	{	
		$expiry_date = Carbon::now()->addDays($this->valid_days);

		if($expiry_date < Carbon::now()) {
			return true;
		}

		return false;
	}

}
