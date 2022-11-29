<?php

namespace App\Http\Requests\API\Care\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {        
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'mobile_number' => ['required', 'regex:/^(09)(\d+)$/', 'size:11'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'regex:/^[A-Za-z0-9]+$/', 'confirmed'],
            'street' => ['required'],
            'region_id' => ['required'],
            'province_id' => [Rule::requiredif(isset(request()->region_id))],
            'city_id' => [Rule::requiredif(isset(request()->province_id))],
            'zip' => ['required', 'regex:/^\d{4}(\d)?(\-\d{4})?$/'],
            'referrer_code' => ['nullable','exists:users,referral_code']
        ];

    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'mobile.required' => 'Mobile number is required',
            'mobile_number.regex' => 'Please enter a valid mobile number',
            'mobile_number.size' => 'Mobile number must be 11 numbers long',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'password.min' => 'Password must be at least 8 characters long',
            'password.regex' => 'Only letters and numbers are allowed',
            'zip.regex' => 'Please enter a valid zip code',
            'referrer_code.exists' => 'Referral code is doesnt exist',
            'region_id.required' => 'Region is required',
            'province_id.required' => 'Province is required'
        ];
    }
}
