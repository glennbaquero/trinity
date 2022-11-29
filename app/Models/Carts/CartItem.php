<?php

namespace App\Models\Carts;

use Illuminate\Database\Eloquent\Model;

use App\Models\Consultations\Consultation;
use App\Models\Carts\Cart;
use App\Models\Products\Product;
use App\Models\Users\Doctor;

use App\Traits\FileTrait;


class CartItem extends Model
{

	use FileTrait;

    protected $guarded = [];
    
    /*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/

	public function cart()
	{
		return $this->belongsTo(Cart::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class)->withTrashed();
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

	/**
	 * Link md to specific cart item
	 * 
	 * @param int $doctorID
	 */
	public function linkMD($doctorID)
	{
		$this->doctor_id = $doctorID;
		$this->save();
	}

	/**
	 * Upload prescription to a specific cart item
	 * 
	 * @param  array $request
	 */
	public function uploadPrescription($request)
	{
		if($request->hasFile('prescription')) {
			$prescription = $request->file('prescription')->store('prescriptions', 'public');
			$this->prescription_path = $prescription;
		}

		if($request->consultation_id) {
			$this->consultation_id = $request->consultation_id;
		}

		if(!isset($request->consultation_id)) {
			$this->consultation_id = null;
		}

		$this->save();
	}

	/**
	 * Get linked MD
	 * 
	 * @return object
	 */
	public function getLinkedMD()
	{
		if($this->doctor) {
			return $this->doctor;
		}
	}

	/**
	 * Get consultation of specific cart item
	 * 
	 */
	public function getConsultation()
	{
		if($this->consultation) {
			return Consultation::dataParser([$this->consultation])[0];
		}
	}

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

	public function hasPrescription()
	{
		if($this->consultation || $this->prescription_path) {
			return true;
		}		
	}

}
