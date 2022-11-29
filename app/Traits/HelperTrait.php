<?php

namespace App\Traits;

use App\Helpers\ObjectHelpers;

trait HelperTrait {

    public function renderName() {
        return;
    }

    public function renderShowUrl() {
        return;
    }

    /**
     * @Helpers
     */
    public static function renderConstants($array, $value, $column = 'label', $compare_column = 'value') {

        /* Loop through the array */
        foreach ($array as $obj) {
            
            if($obj[$compare_column] == $value) {

                /* Fetch columm if specified */
                if($column && isset($obj[$column]))
                    return $obj[$column];

                return $obj;
            }
        }
    }

    public function renderClassName() {
        return ObjectHelpers::getShortClassName($this);
    }

    public function renderLogName() {
        return "{$this->renderClassName()} #{$this->id}";
    }

    public function renderDate($column = 'created_at') {
        $date = null;

        if (isset($this->$column) && $this->$column) {
            $date = $this->$column->format('M d, Y (g:m A)');
        }

        return $date;
    }

    public function renderDateOnly($column = 'created_at') {
        $date = $this->$column->format('M d, Y');
        $time = date("g:i A", strtotime($this->$column->format('H:m')));

        return "{$date}";
    }

    public function renderCreatedAt($column = 'created_at')
     {
        $date = null;

        if (isset($this->$column) && $this->$column) {
            $date = $this->$column->format('M d, Y (g:i A)');
        }

        return $date;
     }

    /**
     * Render price of specified resource from storage
     * 
     * @param  string $column
     * @param  string $format [description]
     * @return  String
     */
    public function renderPrice($column, $format = null)
    {
        if($this->$column) {
            $price = number_format($this->$column, 2, '.', ',');
            if($format) {
                $price = $format. ' ' .$price;
            }

            return $price;
        }
    }
}