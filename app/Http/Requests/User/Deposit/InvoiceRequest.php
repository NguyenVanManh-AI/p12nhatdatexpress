<?php

namespace App\Http\Requests\User\Deposit;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class InvoiceRequest extends FormRequest
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
        $validate_array = array();

        $validate_array =  [
            'invoice_type' => ['required', 'in:1,2'],
            'deposit_type' => ['required', 'in:B,C,S,I'],
            'deposit_code' => ['required', 'exists:user_deposit,deposit_code'],
            'invoice_content' => ['nullable','min:0', 'max:255']
        ];

        if ($request->invoice_type == 2)
        {
            $validate_array['company_name'] = ['required', 'min:1', 'max:255'];
            $validate_array['fullname'] = ['required', 'min:1', 'max:255'];
            $validate_array['address'] = ['required', 'min:1', 'max:255'];
            $validate_array['tax_number'] = ['required', 'min:1', 'max:255'];
        }

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

        Toastr::error('Gửi yêu cầu không thành công');
        return parent::failedValidation($validator);
    }
}
