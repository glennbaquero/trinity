<?php

namespace App\Http\Requests\Admin\TargetCalls;

use Illuminate\Foundation\Http\FormRequest;

class TargetCallStoreRequest extends FormRequest
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
            'medical_representative_id' => 'required',
            'month' => 'required',
            'year' => 'required',
            'target' => 'required|numeric',
        ];

        return $rules;
    }
}
