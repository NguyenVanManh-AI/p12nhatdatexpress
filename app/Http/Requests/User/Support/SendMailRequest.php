<?php

namespace App\Http\Requests\User\Support;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SendMailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        $validate_array =  [
            'mail_title' => ['required', 'max:254'],
            'mail_content' => ['required','max:1000'],
            'mail_type' => ['required', 'in:I,A,S'],
        ];
        return $validate_array;
    }

    public function messages()
    {
        return config('constants.validate_message');
    }

    public function attributes()
    {
        return config('constants.validate_attribute_alias');
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Toastr::error('Gửi thông báo không thành công');
        return parent::failedValidation($validator);
    }
}
