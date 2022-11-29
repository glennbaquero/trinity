<?php

namespace App\Rules;

use App\Extendables\BaseRule as Rule;

class Image extends Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rules = 'image|max:2000';
    }
}
