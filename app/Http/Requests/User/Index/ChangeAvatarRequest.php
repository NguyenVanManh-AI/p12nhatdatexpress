<?php

namespace App\Http\Requests\User\Index;

use App\Http\Requests\BaseRequest;
use Brian2694\Toastr\Facades\Toastr;

class ChangeAvatarRequest extends BaseRequest
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
        $validate_array =  [
            'upload_cover' => ['required', 'mimes:jpeg,jpg,png']
        ];

        return $validate_array;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Toastr::error('Đổi ảnh đại diện không thành công');
        return parent::failedValidation($validator);
    }
}
