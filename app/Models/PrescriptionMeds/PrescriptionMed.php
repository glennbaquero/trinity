<?php

namespace App\Models\PrescriptionMeds;

use App\Extendables\BaseModel as Model;

use App\Models\MedicalPrescriptions\MedicalPrescription;
use App\Models\Products\Product;

class PrescriptionMed extends Model
{
    /*
	|--------------------------------------------------------------------------
	| @Consts
	|--------------------------------------------------------------------------
	*/



	/*
	|--------------------------------------------------------------------------
	| @Attributes
	|--------------------------------------------------------------------------
	*/



	/*
	|--------------------------------------------------------------------------
	| @Relationships
	|--------------------------------------------------------------------------
	*/

	public function medical_prescription() 
	{
		return $this->belongsTo(MedicalPrescription::class)->withTrashed();
	}

	public function product() 
	{
		return $this->belongsTo(Product::class)->withTrashed();
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
