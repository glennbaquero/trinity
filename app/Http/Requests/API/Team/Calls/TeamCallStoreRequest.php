<?php

namespace App\Http\Requests\API\Team\Calls;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamCallStoreRequest extends FormRequest
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
        
        $storeRequired = '';
        $updateRequired = '';
        $after = '';
        if($this->request->get('_method') != 'PUT') {
            $storeRequired = 'required';
            $updateRequired = '';
        }
        if($this->request->get('arrived_at') != 'null' && $this->request->get('arrived_at')) {
           $after = 'after: '. $this->request->get('arrived_at');
       }

        return [
            'doctor_id' => [$storeRequired],
            'clinic' => [$storeRequired],
            'scheduled_date' => [$storeRequired],
            'agenda' => [$storeRequired],
            'arrived_at' => [$updateRequired],
            'left_at' => [$updateRequired, $after],
            

            /** @REMOVE */
            // 'signature' => [$updateRequired],
            // 'attachments' => [$updateRequired],
        ];
    }

    public function messages()
    {
        return [
            'doctor_id.required' => 'Please select a doctor'
        ];
    }
}
