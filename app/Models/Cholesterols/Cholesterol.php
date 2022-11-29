<?php

namespace App\Models\Cholesterols;

use App\Extendables\BaseModel as Model;

use App\Models\Users\User;

use Carbon\Carbon;

class Cholesterol extends Model
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
     * Get status attribute
     * 
     */
    public function getStatusAttribute()
    {
        if($this->total <= 200) {
            return "Normal";
        } else {
            return "Not Normal";
        }
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
        $latest = $user->cholesterols()->latest()->first();

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

        $formulateLDL = self::formulateData($user, $date, 'ldl');
        $formulateHDL = self::formulateData($user, $date, 'hdl');
        $formulateTotal = self::formulateData($user, $date, 'total');

        return [
            [
                'name' => 'LDL',
                'data' => $formulateLDL
            ],

            [
                'name' => 'HDL',
                'data' => $formulateHDL
            ],

            [
                'name' => 'Total Cholesterol',
                'data' => $formulateTotal
            ],            
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
            $cholesterols = $user->cholesterols()->whereDate('created_at', '>=',  $date->startOfDay())
                        ->whereDate('created_at', '<=', $date->endOfDay())->get();            
        } else {
            $cholesterols = collect($user->cholesterols()->latest()->take(7)->get());
            $cholesterols = $cholesterols->sortBy('created_at')->values();
        }
        

        foreach ($cholesterols as $key => $cholesterol) {
            array_push($data, [$cholesterol->created_at->format('M-d h:i A'), $cholesterol->$column]);
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
