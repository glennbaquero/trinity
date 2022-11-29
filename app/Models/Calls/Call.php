<?php

namespace App\Models\Calls;

use App\Models\Calls\CallAttachment;
use App\Models\Users\Doctor;
use App\Models\Users\MedicalRepresentative;

use App\Helpers\StringHelpers;
use Laravel\Scout\Searchable;

use App\Extendables\BaseModel as Model;

class Call extends Model
{

	use Searchable;

	protected $fillable = [
		'medical_representative_id', 'doctor_id', 'clinic', 'scheduled_date',
		'scheduled_time', 'agenda', 'arrived_at', 'left_at', 'notes', 'status', 'signature'
	];

	const FOR_UPDATE = 0;
	const APPROVED = 1;
	const REJECTED = 2;
 
 	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/   
    public function callAttachments()
    {
    	return $this->hasMany(CallAttachment::class);
    }

    public function doctor()
	{
		return $this->belongsTo(Doctor::class)->withTrashed()->with('specialization');
	}

	public function medicalRepresentative()
	{
		return $this->belongsTo(MedicalRepresentative::class)->withTrashed();
	}


	/*
	|--------------------------------------------------------------------------
	| Renders
	|--------------------------------------------------------------------------
	*/
	public function renderArchiveUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'calls.archive', $this->id);
	}

	public function renderRestoreUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'calls.restore', $this->id);
	}

	public function renderShowUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'calls.show', $this->id);
	}

	public function renderStatus() {
		foreach (self::getStatus() as $key => $status) {
            if($status['id'] == $this->status) {
                return $status;
            }
        }

        return null;
	}

	public function renderApproveUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'calls.approve', $this->id);
	}

	public function renderRejectUrl($prefix = 'admin') {
	    return route(StringHelpers::addRoutePrefix($prefix) . 'calls.reject', $this->id);
	}


	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/
	public function getMedRep()
	{
		return $this->medicalRepresentative ? $this->medicalRepresentative->fullname : 'N/A';
	}

	public function getDoctor()
	{
		return $this->doctor ? $this->doctor->fullname : 'N/A';
	}

	public function getStatus() {
		return [
            ['id' => self::FOR_UPDATE, 'value' => 'For Update'],
            ['id' => self::APPROVED, 'value' => 'Approved'],
            ['id' => self::REJECTED, 'value' => 'Rejected'],
        ];
	}


	/*
	|--------------------------------------------------------------------------
	| Methods
	|--------------------------------------------------------------------------
	*/
	public function toSearchableArray()
	{
		return [
			'id' => $this->id,
			'medical_representative' => $this->medicalRepresentative ? $this->medicalRepresentative->fullname : null,
			'doctor_first_name' => $this->doctor ? $this->doctor->first_name : null,
			'doctor_last_name' => $this->doctor ? $this->doctor->last_name : null
		];
	}

	public static function store($request, $item = null, $columns = [
		'medical_representative_id', 'doctor_id', 'clinic', 'scheduled_date',
		'scheduled_time', 'agenda', 'arrived_at', 'left_at', 'notes'
	])
    {
        $vars = $request->only($columns);

        if (!$item) {
            $item = static::create($vars);
        }
        else {
            $item->update($vars);
        }

        return $item;
    }

    public static function filterDoctorsCall($calls, $months, $year)
    {
    	$filterCalls;

		$filterCalls = $calls->map(function($call) {
    		return $call->doctor;
    	})->flatten(1)->groupBy('id')->map(function($group) use ($months, $year){
			
			$dates = $group[0]->calls()->whereIn(\DB::raw('month(scheduled_date)'), $months)->where(\DB::raw('year(scheduled_date)'), $year)->where('status', 1)->selectRaw('scheduled_date')->get();

			return [
				'calls' => $group->count('id'),
				'dates' => $dates,
				'name' => $group[0]['fullname'],
				'class' => $group[0]['class'],

			];
    	})->sortBy('calls')->values();

    	return $filterCalls;
    }

}
