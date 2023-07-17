<?php

namespace App\Http\Requests\User\Conversations;

use App\Enums\ChatType;
use App\Http\Requests\BaseRequest;
use App\Rules\CheckBlockedUser;
use App\Rules\CheckSpammedUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateBasicChatRequest extends BaseRequest
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
        $rules = [
            'chat_type' => 'required|string|' . Rule::in(ChatType::getValues()),
            'message' => [
                new CheckBlockedUser('yêu cầu'),
                new CheckSpammedUser(),
                'required',
                'string',
                'max:2500',
            ],
            'g-recaptcha-response' => 'required|captcha',
        ];

        return $rules;
    }
}
