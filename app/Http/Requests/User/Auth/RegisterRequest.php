<?php

namespace App\Http\Requests\User\Auth;

use App\Http\Requests\BaseRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegisterRequest extends BaseRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     *
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
    public function rules(Request $request)
    {
        $after_date = '1900-01-01';
        $current_date = date('Y-m-d', time());

        $validate_array = [
            'username' => ['required', 'alpha_num', 'unique:user,username', 'min:8', 'max:50'],
            'user_type' => ['required', 'in:1,2,3'],
            'fullname' => ['required', 'min:1', 'max:50'],
            'password' => ['required', 'min:8', 'max:50'],
            're_password' => ['same:password'],
            'phone_number' => ['required', 'regex:/[0-9  ]/', 'unique:user,phone_number', 'min:6', 'max:11'],
            'email' => ['required', 'email', 'unique:user,email', 'max:254'],
            'province' => ['required', 'exists:province,id'],
            'district' => ['required', 'exists:district,id'],
            'ward' => ['required', 'exists:ward,id'],
            'source' => ['nullable', 'in:1,2,3'],
            'tax_number' => ['required', 'unique:user_detail,tax_number', 'min:9', 'max:16'],
            'user_type' => ['required', 'exists:user_type,id'],
            'website' => ['nullable', 'url'],
            'g-recaptcha-response' => 'required|captcha',
        ];

        if ($this->request->get('user_type') == 3) {
            $validate_array['upload-license'] = ['required', 'mimes:jpeg,jpg,png'];

        } else {
            $validate_array['birthday'] = ['date', 'date_format:Y-m-d', 'after_or_equal:' . $after_date, 'before:' . $current_date];

        }

        return $validate_array;
    }


    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Session::flash('popup_display', '#login');
        Toastr::error('Tạo tài khoản không thành công');
        return parent::failedValidation($validator);
    }
}


