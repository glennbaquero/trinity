<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductStorePost extends FormRequest
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
        $imageValidate = '';
        if(!$this->route('id')) {
            $imageValidate = 'required|';
        }        

        $posts = [
            // 'parent_id' => 'required',            
            'specialization_ids' => 'required',
            'name' => 'required',
            'brand_name' => 'required',
            'sku' => 'required',
            'image_path' => $imageValidate.'image',
            'price' => 'required|numeric',
            'client_points' => 'required_if:type,1|numeric',
            'doctor_points' => 'required_if:type,1|numeric',
            'ingredients' => 'required',
            'nutritional_facts' => 'required',
            'directions' => 'required',
            'description' => 'required'
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
            // 'parent_id.required' => 'Please select product parent',
            'image_path.required' => 'Image is required',
            'specialization_ids.required' => 'Specialization is required'
        ];

        return $messages;
    }
}
