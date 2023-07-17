<?php

namespace App\Http\Requests\User\Mail;

use App\Http\Requests\BaseRequest;
use Brian2694\Toastr\Facades\Toastr;

class AddTemplateMailRequest extends BaseRequest
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
        return  [
            'mail_header' => 'required|string|max:255',
            'mail_content' => 'required|string|max:65000',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Toastr::error('Tạo mẫu mail không thành công');
        return parent::failedValidation($validator);
    }
}
