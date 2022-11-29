<?php

namespace App\Http\Requests\Admin\StatusTypes;

use Illuminate\Foundation\Http\FormRequest;

class StatusTypeStorePost extends FormRequest
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
            'name' => 'required',
            'bg_color' => 'required',
            'action_type' => 'required',
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
            'name.required' => 'Status name is required',
            'bg_color.required' => 'Status Label background color is required',
            'action_type.required' => 'Status action type is required',
        ];

        return $messages;

    }

}
