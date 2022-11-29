<?php

namespace App\Http\Requests\Admin\MedicalRepresentatives;

use Illuminate\Foundation\Http\FormRequest;

class MedicalRepresentativeStoreRequest extends FormRequest
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

        $rules = [
            'fullname' => 'required',
        ];

        if ($id) {
            $emailRules = [
                'email' => 'required|email|unique:medical_representatives,email,' . $id,
                // 'password' => 'sometimes',
            ];
        } else {
            $emailRules = [
                'email' => 'required|email|unique:medical_representatives,email',
                // 'password' => 'required',
            ];
        }

        $rules = array_merge($rules, $emailRules);

        return $rules;
    }
}
