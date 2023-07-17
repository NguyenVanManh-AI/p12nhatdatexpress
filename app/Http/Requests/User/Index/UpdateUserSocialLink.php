<?php

namespace App\Http\Requests\User\Index;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSocialLink extends FormRequest
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
            'facebook' => ['url', 'nullable'],
            'youtube' => ['url', 'nullable'],
            'twitter' => ['url', 'nullable']
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
