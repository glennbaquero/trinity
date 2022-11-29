<?php

namespace App\Http\Requests\Admin\Articles;

use Illuminate\Foundation\Http\FormRequest;

class ArticleStoreRequest extends FormRequest
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
        $rules = [
            'overview' => 'required',
            'title' => 'required',
            'date' => 'required',
            'category_id' => 'required',
            'image_path' => 'required',
        ];

        if($this->id) {
            $rules['image_path'] = 'nullable';
        }

        return $rules;
    }
}
