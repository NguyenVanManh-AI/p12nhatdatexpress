<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class CreateStaticPageRequest extends BaseRequest
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
            'page_title' => 'required|unique:static_page,page_title|min:3|max:100',
            // 'page_group' => 'required|integer',
            'image_url' => 'max:255|nullable',
            'page_url' => 'max:255|nullable',
            'meta_title' => 'max:255|nullable',
            'meta_desc' => 'max:255|nullable',
            'meta_key' => 'max:255|nullable',
            'page_description' => 'required|max:255',
            'page_content' => 'required'
        ];
    }
}
