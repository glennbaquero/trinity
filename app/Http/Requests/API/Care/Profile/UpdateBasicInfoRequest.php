<?php

namespace App\Http\Requests\API\Care\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBasicInfoRequest extends FormRequest
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
            'first_name' => [Rule::requiredIf(isset(request()->first_name))],
            'last_name' => [Rule::requiredIf(isset(request()->last_name))],
            'email' => ['email']
        ];
    }
}
