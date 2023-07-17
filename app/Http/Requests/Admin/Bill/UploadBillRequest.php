<?php

namespace App\Http\Requests\Admin\Bill;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use function config;

class UploadBillRequest extends FormRequest
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
            'file' => ['required', 'mimes:jpeg,jpg,png,png,pdf']
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
        Toastr::error('Upload hóa đơn không thành công. Định dạng cho phép jpeg, jpg, png, png, pdf');
        return parent::failedValidation($validator);
    }
}
