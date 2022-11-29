<?php

namespace App\Http\Requests\API\Care\Invoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBasicInfoRequest extends FormRequest
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
            'shipping_name' => ['required'],
            'shipping_email' => ['required', 'email'],
            'shipping_mobile' => ['required', 'regex:/^09(\d+)$/', 'size:11'],
            'region_id' => Rule::requiredIf(request()->new_address),
            'province_id' => [Rule::requiredIf(request()->new_address && isset(request()->region_id))],
            'city_id' => [Rule::requiredIf(request()->new_address && isset(request()->province_id))],
            'street' => Rule::requiredIf(request()->new_address),
            'unit' => Rule::requiredIf(request()->new_address),
            'zip' => Rule::requiredIf(request()->new_address),
        ];
    }

    public function messages()
    {
        return [
            'shipping_mobile.regex' => 'Please enter a valid mobile number',
            'shipping_mobile.size' => 'Mobile number must be 11 numbers long',
            'region_id.required' => 'Region is required',
            'province_id.required' => 'Province is required',
            'city_id.required' => 'City is required',
        ];
    }
}
