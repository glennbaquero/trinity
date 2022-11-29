<?php

namespace App\Http\Requests\Web\Profiles;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\ShortString;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', new ShortString],
            'last_name' => ['required', new ShortString],
            'email' => 'required|email|unique:users,email,' . $this->user()->id,
        ];
    }
}
