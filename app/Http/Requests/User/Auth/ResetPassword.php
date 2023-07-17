<?php

namespace App\Http\Requests\User\Auth;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class ResetPassword extends FormRequest
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
            'email' => ['required','email','exists:user,email'],

            //'g-recaptcha-response' => 'required|captcha',
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
        Session::flash('popup_display', '#reset-password');
        Toastr::error('Đặt lại mật khẩu không thành công');
        return parent::failedValidation($validator);

    }
}
