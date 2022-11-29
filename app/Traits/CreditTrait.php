<?php

namespace App\Traits;

use App\Models\Credits\Credit;

trait CreditTrait {

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function credits()
    {
        return $this->morphMany(Credit::class, 'parent');
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */	


    /**
     * Process user credits
     * 
     * @param  int $value
     */
    public function processCredit($value)
    {
        $this->credits()->create([
            'value' => $value,
        ]);
    }

    /**
     * Count user credits
     *
     * @return int
     */
    public function countCredits($hasFormat = false)
    {
        $total = $this->credits()->sum('value');

        if($total <= 0) {
            $total = 0;
        }

        if($hasFormat) {
        	$total = number_format($total, 2, '.', ',');
        }

        return $total;
    }

}