<?php

namespace App\Http\Requests\User\Mail;

use App\Enums\User\MailConfigEncryption;
use App\Http\Requests\BaseRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EditMailRequest extends BaseRequest
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
        $user = Auth::guard('user')->user();

        return [
            // 'mail_driver' => 'required|between:1,255',
            'mail_host' => 'required|max:255',
            'mail_port' => 'required|integer',
            'mail_encription' => 'required|' . Rule::in(MailConfigEncryption::getValues()),
            'mail_username' => [
                'required',
                'email',
                'max:255',
                Rule::unique('user_mail_config', 'mail_username')->ignore($this->id, 'id')->where(function ($query) use ($user) {
                    return $query->where('is_deleted', 0)
                        ->where('user_id', $user->id);
                })
            ],
            'mail_password' => 'required|max:255',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Toastr::error('Cập nhật cấu hình mail không thành công');
        return parent::failedValidation($validator);
    }
}
