<?php

namespace App\Http\Requests\User;

use App\Enums\User\MailConfigEncryption;
use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MailConfigTestRequest extends BaseRequest
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
            'user_mail' => 'required|email|max:255',
            'mail_host' => 'required|max:255',
            'mail_port' => 'required|integer',
            'mail_encription' => 'required|' . Rule::in(MailConfigEncryption::getValues()),
            'mail_username' => 'required|email|max:255',
            'mail_password' => 'required|max:255',
        ];
    }
}
