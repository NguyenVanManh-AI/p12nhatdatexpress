<?php

namespace App\Http\Requests\Admin\Notify;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;

class CreateNotifyRequest extends FormRequest
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
        return [
            'user_id' => ['required', 'exists:user,id'],
            'mailbox_type' => ['required', 'in:I,A,S'],
            'mail_title' => ['required', 'between:1,255'],
            'mail_content' => ['required', 'between:1,50000'],
        ];
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
        Toastr::error('Tạo thông báo không thành công');
        return parent::failedValidation($validator);
    }
}
