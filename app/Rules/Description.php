<?php

namespace App\Rules;

use App\Extendables\BaseRule as Rule;

class Description extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'max:4000';
    }
}
