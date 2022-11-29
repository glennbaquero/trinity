<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Validator;

class File implements Rule
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
        $validator = Validator::make(['value' => $value], [
            'value' => 'mimes:pdf,docx,doc,odt,ods|max: 2000',
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
        return 'The :attribute must be a valid document format (pdf, docx and or doc) and not exceeds the 2MB';
    }
}
