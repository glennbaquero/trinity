<?php

namespace App\Http\Requests\API\Doctor\Profile;

use Illuminate\Foundation\Http\FormRequest;

class DoctorProfileStoreRequest extends FormRequest
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
        $id = request()->user()->id;

        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_number' => 'required',
            'license_number' => 'required',
            'specialization_id' => 'required', 
        ];
    }
}
