<?php

namespace App\Models\MedicalPrescriptions;

use App\Extendables\BaseModel as Model;

use App\Models\Consultations\Consultation;
use App\Models\PrescriptionMeds\PrescriptionMed;

use App\Services\PushService;

class MedicalPrescription extends Model
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

	public function consultation()
	{
		return $this->belongsTo(Consultation::class, 'consultation_id');
	}

	public function meds()
	{
		return $this->hasMany(PrescriptionMed::class, 'medical_prescription_id');
	}


	/*
	|--------------------------------------------------------------------------
	| @Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Store resource to storage
	 * 
	 * @param  array $request
	 * @param  object $item
	 */
	public static function store($request)
	{

		$vars = $request->except(['meds', 'remaining']);

		$consultation = Consultation::findOrFail($request->consultation_id);
		$item = self::where('consultation_id', $consultation->id)->first();
		
		if(!$item) {
			$item = self::create($vars);
			$item->sendPushNotif();
		} else {
			$item->update($vars);
		}

		$item->storeMeds($request->meds);

		return $item;

	}

	/**
	 * Store meds
	 * 
	 * @param  array $meds
	 */
	public function storeMeds($meds)
	{
		$oldIds = $this->meds()->pluck('id')->toArray();
		$requestIds = [];
		$vars = [];
		
		if(!count($meds)) {
			$this->meds()->whereIn('id', $oldIds)->delete();			
		}

		foreach ($meds as $key => $med) {

			$vars = [
				'name' => $med['name'],
				'dosage' => $med['dosage'],
				'duration' => $med['duration'],
				'notes' => $med['notes']
			];
			
			if(isset($med['product_id'])) {
				$vars['product_id'] = $med['product_id'];
			}

			if(!isset($med['id'])) {
				$medicine = $this->meds()->create($vars);
			} else {
				$medicine = $this->meds()->find($med['id']);
				$medicine->update($vars);
			}

			array_push($requestIds, $medicine->id);
		}

		$deleteableIds = array_diff($oldIds, $requestIds);
		if(count($deleteableIds)) {
			$this->meds()->whereIn('id', $deleteableIds)->delete();
		}
	}

	/**
	 * Fetch resources from storage
	 * 
	 * @param  int $consultationID
	 */
	public static function fetch($consultationID) 
	{
		$item = self::where('consultation_id', $consultationID)->first();
		$meds = $item ? $item->meds: [];

		return [
			'medical_prescription' => $item,
			'meds' => $meds
		];
	}

	/**
	 * Send push notification
	 * 
	 */
	public function sendPushNotif() 
	{
		$title = "Medical Prescription has been created";
		$message = "Your medical prescription for consultation (#{$this->consultation->consultation_number}) has been created";

		$service = new PushService($title, $message);
		$service->pushToOne($this->consultation->user);
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
}
