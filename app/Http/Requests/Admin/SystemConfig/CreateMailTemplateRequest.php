<?php

namespace App\Http\Requests\Admin\SystemConfig;

use App\Http\Requests\BaseRequest;

class CreateMailTemplateRequest extends BaseRequest
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
            'template_title' => 'required|unique:admin_mail_template,template_title',
            'template_action' => 'required|unique:admin_mail_template,template_action',
            'template_content' => 'required'
        ];
    }
}
