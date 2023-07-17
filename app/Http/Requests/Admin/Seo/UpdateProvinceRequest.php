<?php

namespace App\Http\Requests\Admin\Seo;

use App\Http\Requests\BaseRequest;

class UpdateProvinceRequest extends BaseRequest
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
            'seo' => 'array',
            'seo.meta_key' => 'required|string|max:255',
            'seo.meta_title' => 'required|string|max:255',
            'seo.meta_description' => 'required|string|max:255',
        ];
    }
}
