<?php

namespace App\Http\Requests\User\Ref;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShareVipRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $validate_array =  [
            'share_vip_amount' =>  ['required','integer'],
            'user_ref_share_vip' => ['required','exists:user,user_code'],
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
        Session::flash('popup', '#share-vip-amount');
        Toastr::error('Tạo liên kết không thành công');
        return parent::failedValidation($validator);
    }
}
