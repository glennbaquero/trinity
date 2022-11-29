<?php

namespace App\Http\Requests\Admin\Roles;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
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

        if ($id) {
            $rules = [
                'name' => 'required|unique:roles,name,' . $id,
            ];
        } else {
            $rules = [
                'name' => 'required|unique:roles,name',
            ];
        }

        array_merge($rules, [

        ]);

        return $rules;
    }
}
