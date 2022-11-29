<?php

namespace App\Http\Requests\API\Care\Addresses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddressStoreRequest extends FormRequest
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
            'region_id' => ['required'],
            'province_id' => [Rule::requiredIf(isset(request()->region_id))],
            'city_id' => [Rule::requiredIf(isset(request()->province_id))],
            'street' => ['required'],
            'zip' => ['required', 'regex:/^\d{4}(\d)?(\-\d{4})?$/'],
        ];
    }

    public function messages() 
    {
        return [
            'required' => '',
            'zip.regex' => 'Please enter a valid zip code'
        ];
    }
}
