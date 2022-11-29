<?php

namespace App\Http\Requests\Samples;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\ShortString;
use App\Rules\DateTime;
use App\Rules\Image;

class SampleItemStoreRequest extends FormRequest
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
            'sample_item_id' => 'required|exists:sample_items,id',
            'data' => 'required',
            'data.*' => 'exists:sample_items,id',
            'date' => ['required', new DateTime],
            'dates' => 'required',
            'dates.*' => ['required', new DateTime],
            'status' => 'required',
        ];

        if (!$id) {
            $rules = array_merge($rules, [
                'images' => 'required',
                'images.*' => 'image',
                'image_path' => 'required|image',
            ]);
        } else {
            $rules = array_merge($rules, [
                'images' => 'nullable',
                'images.*' => [new Image],
                'image_path' => 'nullable|image',
            ]);
        }

        return $rules;
    }
}
