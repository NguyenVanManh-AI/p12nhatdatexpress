<?php

namespace App\Http\Requests\Admin\PromotionNews;

use App\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
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
            'news_title' => 'required|max:255',
            'news_description' => 'required|max:255',
            'news_content' => 'required|max:10000',
            // 'image'=>'image|mimes:jpeg,jpg,png,bmp,gif,svg|max:2048',
        ];
    }
}
