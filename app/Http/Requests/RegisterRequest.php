<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * Get the validation rules that apply to the request for Users and Doctors.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:doctors,email',
            'password' => 'required|confirmed', 
            'mobile_number' => 'required',
            'alma_mater' => 'required',
            'place_of_practice' => 'required',
            'license_number' => 'required',
            'specialization_id' => 'required',
            'referrer_code' => 'nullable|exists:users,referral_code'
        ];
    }
}
