<?php

namespace App\Models\HeartRates;

use App\Extendables\BaseModel as Model;

use App\Models\Users\User;

use Carbon\Carbon;

class HeartRate extends Model
{
	/*
    |--------------------------------------------------------------------------
    | @Relationships
    |--------------------------------------------------------------------------
    */	

    public function patient() 
    {
    	return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | @Attributes
    |--------------------------------------------------------------------------
    */
   
    protected $appends = ['status'];

    /**
     * Get status attributes
     * 
     */
    public function getStatusAttribute()
    {

        $status = true;

        if(!$this->checkHeartRate()) {
            $status = false;
        }

        return $status ? 'Normal': 'Not Normal';

    }


    /*
    |--------------------------------------------------------------------------
    | @Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Fetch latest blood pressures
     * 
     */
    public static function fetchLatest($user)
    {
        $latest = $user->heart_rates()->latest()->first();

        if($latest) {
            return $latest;
        }
    }

    /**
     * Fetch heart rate chart
     * 
     */
    public static function fetchChart($request, $user)
    {
        $date = '';
        if($request->date) {
            $date = Carbon::parse($request->date);
        }

        $formulateOverAllData = self::formulateData($user, $date);
        $formulateNormalData = self::formulateData($user, $date, true);

        return [
            [
                'name' => 'Overall Heart Rate',
                'data' => $formulateOverAllData
            ],

            [
                'name' => 'Normal Heart Rate',
                'data' => $formulateNormalData,
            ]
        ];
    }


    /**
     * Formulate data
     * 
     * @param  boolean $normal
     */
    public static function formulateData($user, $date, $normal = false) 
    {
        $data = [];

        if($date) {        
            $heartRates = $user->heart_rates()->whereDate('created_at', '>=',  $date->startOfDay())
                        ->whereDate('created_at', '<=', $date->endOfDay())->get();
        } else {
            $heartRates = collect($user->heart_rates()->latest()->take(7)->get());
            $heartRates = $heartRates->sortBy('created_at')->values();
        }

        foreach ($heartRates as $key => $heartRate) {
            $value = $heartRate->value;
            if($normal) {
                if(!$heartRate->checkHeartRate()) {
                    $value = null;
                }                
            }

            array_push($data, [$heartRate->created_at->format('M-d h:i A'), $value]);
        }

        return $data;
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
    
    /**
     * Check heart rate
     * 
     */
    public function checkHeartRate()
    {
        if($this->value < 100) {
            return true;
        }
    }
}
