<?php

namespace App\Http\Requests\Admin\ShippingMatrixes;

use Illuminate\Foundation\Http\FormRequest;

class ShippingMatrixRequest extends FormRequest
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

        $posts = [
            'area_id' => 'required',
            'fee' => 'required|numeric',
            'quantity_minimum' => 'nullable|numeric',
            'price_minimum' => 'nullable|numeric',            
        ];

        return $posts;
    }


    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {

        $messages = [
            'area_id.required' => 'Please select an area',
            'fee.required' => 'Shipping fee is required',
            'fee.numeric' => 'Shipping fee must be in number format',
            'quantity_minimum.numeric' => 'Quantity must be in number format',
            'price_minimum.numeric' => 'Price must be in number format',            
        ];
        
        return $messages;
    }

}
