<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductParentRequest extends FormRequest
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
            'specialization_id' => 'required',
            'name' => 'required',
            'image_path' => $imageValidate.'image',            
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
            'specialization_id.required' => 'Please select specialization',
            'name.required' => 'Name is required',
            'image_path.required' => 'Image is required'
        ];

        return $messages;
    }

}
