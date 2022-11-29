<?php

namespace App\Http\Requests\Admin\Areas;

use Illuminate\Foundation\Http\FormRequest;

class AreaRequest extends FormRequest
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
            'name' => 'required|unique:areas,name,'. $id,
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
            'name.required' => 'Area name is required',
            'name.unique' => 'Area name already exists'
        ];
        
        return $messages;
    }

}
