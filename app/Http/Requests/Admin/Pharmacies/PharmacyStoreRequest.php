<?php

namespace App\Http\Requests\Admin\Pharmacies;

use Illuminate\Foundation\Http\FormRequest;

class PharmacyStoreRequest extends FormRequest
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
        $id = $this->route('id');        

        $posts = [
            'name' => 'required',
            'image_path' => $id ? '' : 'required'
        ];

        return $posts;
    }

    public function messages() 
    {
        return [
            'image_path.required' => 'Image is required.'
        ];
    }
}
