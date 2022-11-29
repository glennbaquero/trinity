<?php

namespace App\Models\Bmis;

use App\Extendables\BaseModel as Model;

use App\Models\Users\User;

use Carbon\Carbon;

class Bmi extends Model
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
        return $this->checkBmi();
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
        $latest = $user->bmis()->latest()->first();

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
        /*    $formulateNormalData = self::formulateData($user, $date, 'Normal');
        $formulateUnderweightData = self::formulateData($user, $date, 'Underweight');
        $formulateOverweightData = self::formulateData($user, $date, 'Overweight');
        $formulateObeseData = self::formulateData($user, $date, 'Obese'); */

        return [
            [
                'name' => 'Overall',
                'data' => $formulateOverAllData
            ],

          /*   [
                'name' => 'Normal',
                'data' => $formulateNormalData,
            ],

            [
                'name' => 'Underweight',
                'data' => $formulateUnderweightData,
            ],

            [
                'name' => 'Overweight',
                'data' => $formulateOverweightData,
            ],

            [
                'name' => 'Obese',
                'data' => $formulateObeseData,
            ], */
        ];
    }


    /**
     * Formulate data
     *
     * @param  boolean $normal
     */
    public static function formulateData($user, $date, $type = null)
    {
        $data = [];

        if($date) {
            $bmis = $user->bmis()->whereDate('created_at', '>=',  $date->startOfDay())
                        ->whereDate('created_at', '<=', $date->endOfDay())->get();            
        } else {
            $bmis = collect($user->bmis()->latest()->take(7)->get());
            $bmis = $bmis->sortBy('created_at')->values();
        }

        foreach ($bmis as $key => $bmi) {
            $value = $bmi->value;
            if($type) {
                if($bmi->checkBmi() != $type) {
                    $value = null;
                }
            }

            array_push($data, [$bmi->created_at->format('M-d h:i A'), $value]);
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
     * Check bmi
     *
     */
    public function checkBmi()
    {
        $status = 0;

        switch ($this->value) {
            case $this->value >= 18.50 && $this->value <= 22.99:
                $status = "Normal";
                break;
            case $this->value < 18.50:
                $status = "Underweight";
                break;
            case $this->value >= 23.00 && $this->value <= 24.99:
                $status = "Overweight";
                break;
            case $this->value >= 25:
                $status = "Obese";
                break;
        }

        return $status;

    }
}
