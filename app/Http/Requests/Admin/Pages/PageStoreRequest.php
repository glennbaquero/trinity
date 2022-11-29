<?php

namespace App\Http\Requests\Admin\Pages;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\ShortString;

class PageStoreRequest extends FormRequest
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
            'name' => ['required', new ShortString],
        ];

        if ($id) {
            $rules = array_merge($rules, [
                'slug' => 'required|unique:pages,slug,' . $id,
            ]);
        } else {
            $rules = array_merge($rules, [
                'slug' => 'required|unique:pages,slug',
            ]);
        }

        return $rules;
    }
}
