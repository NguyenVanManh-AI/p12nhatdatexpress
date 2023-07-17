<?php

namespace App\Http\Requests\User\Deposit;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class DepositPackageRequest extends FormRequest
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
        $userId = auth()->guard('user')->user()->id;
        $validate_array =  [
            'deposit_voucher' => ['nullable', "exists:user_voucher,voucher_code,user_id,$userId"],
            'package_id' => ['required', 'exists:classified_package,id'],
            'payment_method' => ['required','in:0,1,2,3'],
            'deposit_code' => ['required']
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

        Toastr::error('Thực hiện thanh toán không thành công');
        return parent::failedValidation($validator);
    }
}
