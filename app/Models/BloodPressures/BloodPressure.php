<?php

namespace App\Models\BloodPressures;

use App\Extendables\BaseModel as Model;

use App\Models\Users\User;

use Carbon\Carbon;

class BloodPressure extends Model
{

    static $labels = [
        '12', '1', '2', '3', '4', '5', '6'
    ];

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
     * Get status attribute
     * 
     */
    public function getStatusAttribute()
    {
        $status = true;

        if(!$this->checkBloodPressure('systole')) {
            $status = false;
        }

        if(!$this->checkBloodPressure('diastole')) {
            $status = $status ? false:  $status;
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
    public static function  fetchLatest($user)
    {
        $latest = $user->blood_pressures()->latest()->first();

        if($latest) {
            return $latest;
        }
    }


    /**
     * Fetch blood pressure chart
     * 
     */
    public static function fetchChart($request, $user)
    {
        $date = '';
        if($request->date) {
            $date = Carbon::parse($request->date);
        }

        $formulateSystole = self::formulateData($user, $date, 'systole');
        $formulateDiastole = self::formulateData($user, $date, 'diastole');

        return [
            [
                'name' => 'Diastole',
                'data' => $formulateDiastole,
            ],

            [
                'name' => 'Systole',
                'data' => $formulateSystole 
            ]

        ];
    }


    /**
     * Formulate data
     * 
     * @param  boolean $normal
     */
    public static function formulateData($user, $date, $column) 
    {
        $data = [];
        if($date) {
            $bloodPressures = $user->blood_pressures()->whereDate('created_at', '>=',  $date->startOfDay())
                        ->whereDate('created_at', '<=', $date->endOfDay())->get();            
        } else {
            $bloodPressures = collect($user->blood_pressures()->latest()->take(7)->get());
            $bloodPressures = $bloodPressures->sortBy('created_at')->values();
        }
        
        foreach ($bloodPressures as $key => $bloodPressure) {
            array_push($data, [$bloodPressure->created_at->format('M-d h:i A'), $bloodPressure->$column]);
        }

        return $data;
    }

    /**
     * Compute total blood pressure
     * Ref: https://www.ncbi.nlm.nih.gov/books/NBK268/
     * 
     * @param  int $systole
     * @param  int $diastole
     */
    public static function computeBloodPressure($systole, $diastole) 
    {
        
        $map = 2 * $diastole + $systole;
        $map = $map / 3;

        return round($map, 2);
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
     * Check blood pressure level
     * 
     * @param  string $type
     */
    public function checkBloodPressure($type)
    {
        switch ($type) {
            case 'systole':
                $status = $this->systole <= 120 ? true: false;
                return $status;
                break;
            case 'diastole':
                $status = $this->diastole <= 80 ? true: false;
                return $status;
                break;
        }

        return false;
    }
}
