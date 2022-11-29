<?php

namespace App\Http\Requests\Admin\AdminUsers;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\ShortString;

class AdminUserStoreRequest extends FormRequest
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
            'first_name' => ['required', new ShortString],
            'last_name' => ['required', new ShortString],
            'role_ids' => 'required',
            'role_ids.*' => 'exists:roles,id',
        ];

        if ($id) {
            $emailRules = [
                'email' => 'required|email|unique:admins,email,' . $id,
            ];
        } else {
            $emailRules = [
                'email' => 'required|email|unique:admins,email',
            ];
        }

        $rules = array_merge($rules, $emailRules);

        return $rules;
    }

    public function messages() {
        return [
            'role_ids.required' => 'Please select a role',
            'role_ids.*.exists' => 'Role no longer exists',
        ];
    }
}
