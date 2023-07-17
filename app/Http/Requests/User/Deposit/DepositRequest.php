<?php

namespace App\Http\Requests\User\Deposit;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DepositRequest extends FormRequest
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

            'deposit_amount' => ['required', 'in:1,2,3,4,5,6,7,8'],
            'deposit_voucher' => ['nullable', 'min:4', 'max:5'],
            'deposit_code' => ['required', 'min:10', 'max:10'],
            'payment_method' => ['required','in:1,2,3'],
            'confirm_payment' => ['required','in:on'],
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
