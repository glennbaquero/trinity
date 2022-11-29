<?php

namespace App\Rules;

use App\Extendables\BaseRule as Rule;

class Date extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'date|date_format:Y-m-d';
        $this->message = 'The :attribute is an invalid date format';
    }
}
