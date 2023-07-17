<?php

namespace App\Http\Requests\Admin\Mails;

use App\Http\Requests\BaseRequest;

class CreateTemplateRequest extends BaseRequest
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
            'template_title' => 'required|string|max:250',
            'template_content' => 'required|string|max:3000',
        ];
    }
}
