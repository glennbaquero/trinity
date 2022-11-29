<?php

namespace App\Http\Requests\Admin\Announcements;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementStoreRequest extends FormRequest
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
        $required = 'nullable';
        if(!$id) {
            $required = 'required';
        }

        return [
            'announcement_type_id' => 'required',
            'announce_to' => 'required',
            'title' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'announcement_type_id.required' => 'Announcement Type is required.',
            'announce_to.required' => 'Announce To is required.',
        ];
    }
}
