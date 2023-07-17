<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CreateEventRequest extends BaseRequest
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
        $unique =  'unique:event,event_title';

        if ($this->route('id')){
            $unique = Rule::unique('event','event_title')->ignore( $this->route('id'));
        }

        $validate_array =  [
            'event_title' => ['required', 'between:3,100', $unique],
            'take_place' => ['required', 'between:3,100'],
            'start_date' => ['required', 'between:1,255', 'after:today'],
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

        return $validate_array;
    }

    public function messages()
    {
        return array_merge(config('constants.validate_message'), [
            'date_format' => ':attribute phải được định dạng dd/mm/yyyy và không khoảng cách',
            'after' => ':attribute phải sau ngày hôm nay',
            'start_time.date_format' => 'Thời gian diễn ra phải được định dạng hh:mm và không khoảng cách'
        ] );
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Session::flash('popup_display', '#create-event');
        Toastr::error('Thêm sự kiện không thành công');
        return parent::failedValidation($validator);
    }
}
