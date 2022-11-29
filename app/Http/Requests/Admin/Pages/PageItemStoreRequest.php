<?php

namespace App\Http\Requests\Admin\Pages;

use Illuminate\Foundation\Http\FormRequest;

class PageItemStoreRequest extends FormRequest
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
            'page_id' => 'required|exists:pages,id',
            'slug' => 'required',
            'name' => 'required',
        ];
    }

    public function messages() {
        return [
            'page_id.required' => 'Please select a page',
            'page_id.exists' => 'Page no longer exists',
        ];
    }
}
