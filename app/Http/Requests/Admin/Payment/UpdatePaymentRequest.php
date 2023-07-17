<?php

namespace App\Http\Requests\Admin\Payment;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdatePaymentRequest extends FormRequest
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
        $validate = [
            'payment_name' => ['required', 'max:100'],
            'qr' => ['nullable', 'max:255']
        ];

        if (!$request->stk){
            $validate['qr'] = ['required', 'max:255'];
        }

        if (!$request->has('qr')){
            $validate['stk'] = ['required', 'max:50'];
            $validate['nh'] = ['required', 'max:100'];
            $validate['cn'] = ['required', 'max:100'];
            $validate['ctk'] = ['required', 'max:100'];
        }

        return $validate;
    }

    public function messages()
    {
        return [
            'stk' => 'Số tài khoản',
            'nh' => 'Ngân hàng',
            'cn' => 'Chi nhánh',
            'ctk' => 'Chủ tài khoản'
        ];
    }

    public function attributes()
    {
        return config('constants.validate_attribute_alias');
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Toastr::error('Thay đổi phương thức thanh toán không thành công');
        return parent::failedValidation($validator);
    }
}
