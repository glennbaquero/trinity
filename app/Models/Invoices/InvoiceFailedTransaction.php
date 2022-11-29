<?php

namespace App\Models\Invoices;

use Illuminate\Database\Eloquent\Model;

use App\Models\Invoices\Invoice;

use Carbon\Carbon;

class InvoiceFailedTransaction extends Model
{

	protected $guarded = [];

    /*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/

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

	/**
	 * Render date of speicified failed transacton
	 * 
	 * @return Date
	 */
	public function renderDate()
	{
        $date = $this->created_at->format('M d, Y (H:m:i)');
        return $date;
	}

    /*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/

}

