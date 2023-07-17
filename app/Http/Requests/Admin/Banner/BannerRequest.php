<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BannerRequest extends FormRequest
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
        $unique =  'unique:banner,banner_title';
        if ($this->route('id')){
            $unique = Rule::unique('banner','banner_title')->ignore( $this->route('id'));
        }
        $validate_array =  [
            'banner_group_id' => ['required', 'exists:banner_group,id'],
            'group_id' => ['required', 'exists:group,id'],
            'banner_title' => ['required', 'between:1,255', $unique],
            'image_url' => ['nullable', 'required_without:banner_code', 'between:1,255'],
            'banner_code' => ['nullable', 'required_without:image_url', 'between:1,50000'],
            'link' => ['nullable', 'between:1,255'],
            'target_type' => ['required'],
        ];
        return $validate_array;
    }

    public function messages()
    {
        return config('constants.validate_message');
    }

    public function attributes()
    {
        return array_merge(
            config('constants.validate_attribute_alias'),
            ['group_id' => 'Chuyên mục']
        );
    }
}
