<?php

namespace App\Http\Requests\Admin\Calls;

use Illuminate\Foundation\Http\FormRequest;

class CallRequest extends FormRequest
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
        return [
            'medical_representative_id' => ['required'],
            'doctor_id' => ['required'],
            'clinic' => ['required'],
            'scheduled_date' => ['required'],
            // 'scheduled_time' => ['required'],
            'agenda' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'medical_representative_id.required' => 'Please choose a medical representative',
            'doctor_id.required' => 'Please choose a doctor',
        ];
    }
}
