<?php

namespace App\Http\Requests\Admin\MedRepTargets;

use Illuminate\Foundation\Http\FormRequest;

class MedRepTargetStorePost extends FormRequest
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
            'type' => "required",
            'month' => 'required',
            'year' => 'required',
            'value' => 'required|numeric'
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
            'value.required' => 'Target is required',
            'value.numeric' => 'Target must be in number format'
        ];

        return $messages;
    }
}
