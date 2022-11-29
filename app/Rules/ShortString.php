<?php

namespace App\Rules;

use App\Extendables\BaseRule as Rule;

class ShortString extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'string|max:50';
    }
}
