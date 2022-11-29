<?php

namespace App\Models\Referrals;

use Illuminate\Database\Eloquent\Model;

use App\Models\Users\User;
use App\Models\Invoices\Invoice;

class SuccessReferral extends Model
{

    protected $fillable = [ 'referrer_id', 'referee_id', 'invoice_id' ];

    /*
    |--------------------------------------------------------------------------
    | @Const
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | @Relationships
    |--------------------------------------------------------------------------
    */
    
    public function referrer()
    {
    	return $this->belongsTo(User::class, 'referrer_id')->withTrashed();
    }

    public function referee()
    {
    	return $this->belongsTo(User::class, 'referee_id')->withTrashed();
    }

    public function invoice()
    {
    	return $this->belongsTo(Invoice::class);
    }

    /*
    |--------------------------------------------------------------------------
    | @Methods
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | @Renders
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | @Checkers
    |--------------------------------------------------------------------------
    */

}
