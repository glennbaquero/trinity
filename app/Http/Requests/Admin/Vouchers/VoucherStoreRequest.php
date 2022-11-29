<?php

namespace App\Http\Requests\Admin\Vouchers;

use Illuminate\Foundation\Http\FormRequest;

class VoucherStoreRequest extends FormRequest
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
            'voucher_type' => 'required',
            'name' => 'required|unique:vouchers,name,'. $this->id,
            'code' => 'required|unique:vouchers,code,'. $this->id,
            'discount' => 'required|numeric|min: 1',
            'user_type' => 'required',
            'valid_days' => 'required|numeric|min: 1',
            'max_usage' => 'required|numeric|min: 1',
            'type' =>   'required'
        ];

        return $rules;
    }
}
