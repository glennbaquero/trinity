<?php

namespace App\Models\MedRepLocationLogs;

use Illuminate\Database\Eloquent\Model;

use App\Models\Users\MedicalRepresentative;

class MedRepLocationLog extends Model
{
	protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | @Relationships
    |--------------------------------------------------------------------------
    */
   	
   	public function medrep()
   	{
   		return $this->belongsTo(MedicalRepresentative::class, 'medical_representative_id')->withTrashed();
   	}

}
