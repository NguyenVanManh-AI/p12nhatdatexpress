<?php

namespace App\Http\Requests\User\Index;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends FormRequest
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
        $validate_array =  [
            'new_password' => ['required', 'min:8', 'max:50'],
            're_password' => ['required','same:new_password'],
            'current_password' => ['required', function ($attribute, $value, $fail) {
                $user = Auth::guard('user')->user();
                if (!Hash::check($value, $user->password)) {
                    return $fail('Mật khẩu không đúng');
                }
            }]
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
}
