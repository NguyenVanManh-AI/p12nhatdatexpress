<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class SendMessageRequest extends BaseRequest
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
            'message' => 'required|string|max:2500'
        ];

        if (request()->hasFile('attach')) {
            $rules['attach'] = 'required|file|mimes:mp4,qt,mov,ogg,jpg,jpeg,png,gif|max:5120';
        }

        return $rules;
    }
}
