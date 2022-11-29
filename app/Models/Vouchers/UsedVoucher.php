<?php

namespace App\Models\Vouchers;

use Illuminate\Database\Eloquent\Model;

use App\Models\Users\User;
use App\Models\Vouchers\Voucher;
use App\Models\Invoices\Invoice;

class UsedVoucher extends Model
{

    protected $guarded = [];

    /**
     * Relationships
     */
    
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function userVoucher() 
    {
    	return $this->belongsTo(UserVoucher::class);
    }

    public function user() 
    {
    	return $this->belongsTo(User::class);
    }

    public function invoice() 
    {
    	return $this->belongsTo(Invoice::class);
    }

    /**
     * Renderer
     */
    
    public function renderTotalDiscount() {
	    return '₱ '. number_format($this->total_discount, 2, '.', ',');
    }

    public function renderVoucherName()
    {
        if($this->userVoucher) {
            return $this->userVoucher->renderName();
        } else {
            return $this->voucher->renderName();
        }
    }

    public function renderVoucherType()
    {
        if($this->userVoucher) {
            return $this->userVoucher->renderType();
        } else {
            return $this->voucher->renderType();
        }
    }

}
