<?php

namespace App\Http\Requests\Admin\ShippinFees;

use Illuminate\Foundation\Http\FormRequest;

class StandardStoreRequest extends FormRequest
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
        $rules = [
            'fee' => 'required|numeric|min:1',
            'province_id' => 'required',
        ];

        return $rules;
    }

    public function messages() 
    {
        return [
            'fee.required' => 'Shipping Fee is required',
            'fee.numeric' => 'Shipping Fee must be a number',
            'fee.min' => 'Shipping Fee must be a greater than to 0',
            'province_id.required' => 'Province is required',
        ];
    }
}
