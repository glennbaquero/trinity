<?php

namespace App\Http\Requests\Admin\Rewards;

use Illuminate\Foundation\Http\FormRequest;

class RewardStoreRequest extends FormRequest
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
            'name' => 'required',
            // 'reward_category_id' => 'required',
            // 'points' => 'required|numeric|min:1',
            'image_path' => 'required|image',
            'description' => 'required'
        ];

        if($this->id) {
            $rules['image_path'] = 'nullable|image';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            // 'reward_category_id.required' => 'Please choose a reward category',
            'points.numeric' => 'Points must be a type of number',
            'points.min' => 'Points must be greater than to 1',
        ];
    }
}
