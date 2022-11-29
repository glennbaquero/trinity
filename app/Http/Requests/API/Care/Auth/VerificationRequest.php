<?php

namespace App\Http\Requests\API\Care\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerificationRequest extends FormRequest
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
            'option' => ['required'],
            'qr_id' => [
                Rule::requiredIf(request()->option === 1 || request()->option === 2),
                'nullable',
            ],
            'prescription' => [
                Rule::requiredIf(request()->option === 3),
                'nullable',
            ],
        ];
    }
}
