<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class SendAdvisoryRequest extends BaseRequest
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
            'fullname' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|max:12',
            'note' => 'max:300'
        ];
    }
}
