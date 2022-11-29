<?php

namespace App\Http\Requests\Admin\Specializations;

use Illuminate\Foundation\Http\FormRequest;

class SpecializationsStorePost extends FormRequest
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
            'name' => 'required|max: 99',
            'image_path' => $imageValidate.'image'
        ];

        return $posts;
    }

    public function messages() 
    {
        return [
            'image_path.requred' => 'Icon is required.',
            'image_path.image' => 'Icon must be a valid image.'
        ];
    }
}
