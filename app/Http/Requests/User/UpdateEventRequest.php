<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use Carbon\Carbon;

class UpdateEventRequest extends BaseRequest
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

    public function prepareForValidation()
    {
        $this->merge([
            'event_content' => htmlspecialchars(request()->event_content),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'event_title' => 'required|between:3,100|unique:event,event_title,' . $this->event->id,
            'take_place' => ['required', 'between:3,100'],
            'start_date' => ['required', 'between:1,255', 'after:' . now()->format('d-m-Y')],
            'start_time' => ['required', 'between:1,255'],
            'event_content' => ['required', 'between:1,5000'],
            // 'image_header_url' => ['required', 'between:1,5120'],
            'image_header_url' => ['required'], // need validate size
            // 'image_invitation_url' => ['nullable', 'between:1,5120'],
            'image_invitation_url' => ['nullable'], // need validate size
            'event_url' => ['required', 'between:1,255'],
            'meta_title' => ['nullable', 'between:3,255'],
            'meta_key' => ['nullable', 'between:1,255'],
            'meta_desc' => ['nullable', 'between:1,255'],
            'map_latitude' => ['required', 'between:1,255'],
            'map_longtitude' => ['required', 'between:1,255'],
            'province_id' => ['required', 'exists:province,id'],
            'district_id' => ['required', 'exists:district,id'],
            'address' => ['required', 'between:1,255'],
        ];

        return $rules;
    }
}
