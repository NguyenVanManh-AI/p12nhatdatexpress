<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    public function messages()
    {
        return config('constants.validate_message', []);
    }

    public function attributes()
    {
        return config('constants.validate_attribute_alias', []);
    }
}
