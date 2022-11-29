<?php

namespace App\Http\Requests\Admin\RequestClaimReferrals;

use Illuminate\Foundation\Http\FormRequest;

class RequestClaimReferralRequest extends FormRequest
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
        if($this->route('action') == 'approve') {
            return [
                'voucher_id' => 'required'
            ];
        } else {
            return [
                'reason' => 'required'
            ];
        }
        
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages() 
    {
        return [
            'voucher_id.required' => 'Please select voucher',
            'reason.required' => 'Please add reason',
        ];
    }
}
