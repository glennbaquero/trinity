<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Validator;

class HTMLText implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $validator = Validator::make(['value' => strip_tags($value)], [
            'value' => 'max:4000',
        ]);

        return !$validator->fails();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute max length is 4000 characters';
    }
}
