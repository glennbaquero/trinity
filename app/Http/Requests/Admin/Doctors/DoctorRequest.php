<?php

namespace App\Http\Requests\Admin\Doctors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DoctorRequest extends FormRequest
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
            'first_name' => ['required'],
            'last_name' => ['required'],
            'medical_representative_id' => ['required'],
            'specialization_id' => ['required'],
            'email' => [
                Rule::requiredIf(request()->is('admin/doctors/create')),
                'email',
                'unique:doctors'
            ],
            // 'mobile_number' => ['regex:/^[0-9]{11}$/', 'size:11'],
            'clinic_address' => ['required', 'string'],
            'clinic_hours' => ['required'],
            'class' => ['required'],
            'consultation_fee' => ['required'],
            'license_number' => ['required'],

        ];
    }

    public function messages()
    {
        return [
            'medical_representative_id.required' => 'Please choose a medical representative',
            'specialization_id.required' => 'Please choose a specialization',
            'mobile.regex' => 'Mobile number must be numeric and 11 characters long'
        ];
    }

}
