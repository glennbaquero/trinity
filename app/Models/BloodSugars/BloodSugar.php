<?php

namespace App\Models\BloodSugars;

use App\Extendables\BaseModel as Model;

use App\Models\Users\User;

use Carbon\Carbon;

class BloodSugar extends Model
{
    protected $table = 'blood_sugars';

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
        return 'Normal';
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
        $latest = $user->blood_sugars()->latest()->first();

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

        $formulateOverAllData = self::formulateData($user, $date);

        return [
            [
                'name' => 'Overall Blood Sugar',
                'data' => $formulateOverAllData
            ],

        ];
    }


    /**
     * Formulate data
     * 
     * @param  boolean $normal
     */
    public static function formulateData($user, $date) 
    {
        $data = [];

        if($date) {
            $bloodSugars = $user->blood_sugars()->whereDate('created_at', '>=',  $date->startOfDay())
                        ->whereDate('created_at', '<=', $date->endOfDay())->get();
        } else {
            $bloodSugars = collect($user->blood_sugars()->latest()->take(7)->get());
            $bloodSugars = $bloodSugars->sortBy('created_at')->values();
        }

        foreach ($bloodSugars as $key => $bloodSugar) {
            array_push($data, [$bloodSugar->created_at->format('M-d h:i A'), $bloodSugar->value]);
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
   
}
