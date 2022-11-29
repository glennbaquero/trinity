<?php

namespace App\Models\Invoices;

use App\Models\Consultations\Consultation;
use App\Models\Products\Product;
use App\Models\Invoices\Invoice;
use App\Models\Users\Doctor;

use App\Traits\HelperTrait;
use App\Traits\FileTrait;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{

	use HelperTrait;
	use FileTrait;
	    
	/**
	 * Attributes that are mass assignable
	 * 
	 * @var array
	 */
    protected $guarded = [];

    /*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/

	public function product()
	{
		return $this->belongsTo(Product::class)->withTrashed();
	}

	public function invoice()
	{
		return $this->belongsTo(Invoice::class)->withTrashed();
	}

	public function doctor()
	{
		return $this->belongsTo(Doctor::class)->withTrashed();
	}

	public function consultation()
	{
		return $this->belongsTo(Consultation::class, 'consultation_id');
	}

	/*
	|--------------------------------------------------------------------------
	| @Methods
	|--------------------------------------------------------------------------
	*/

	public function computeLineTotal($hasFormat = false)
	{
		$total = 0;

		$item = json_decode($this->data);		
		$total = $item->price * $this->quantity;

		if($hasFormat) {
			$total = 'Php ' . number_format($total, 2, '.', ',');
		}
		return $total;

	}

	/*
	|--------------------------------------------------------------------------
	| @Renders
	|--------------------------------------------------------------------------
	*/	

	public function renderEncodedItem()
	{
		$item = json_decode($this->data);

		return $item;
	}

	public function renderDoctor()
	{
		if($this->doctor) {
			return [
				'name' => $this->doctor->renderName(),
				'showUrl' => $this->doctor->renderShowUrl()
			];
		}
		return 'N/A';
	}

	public function renderPrescriptionPath()
	{
		if($this->prescription_path) {
			return $this->renderImagePath('prescription_path');
		}
	}


	public function renderConsultation()
	{
		if($this->consultation) {
			return $this->consultation->consultation_number;
		}
		return 'N/A';
	}

	/*
	|--------------------------------------------------------------------------
	| @Checkers
	|--------------------------------------------------------------------------
	*/	

}
