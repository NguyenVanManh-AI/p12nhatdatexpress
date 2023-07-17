<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BannerGroupRequest extends FormRequest
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
        $unique =  'unique:banner_group,banner_name';
        if ($this->route('id')){
            $unique = Rule::unique('banner_group','banner_name')->ignore( $this->route('id'));
        }
        $validate_array =  [
            'banner_group' => ['required', 'max:1'],
            'banner_group_name' => ['required', 'between:1,255'],
            'banner_permission' => ['nullable', 'max:1'],
            'banner_type' => ['required', 'max:1'],
            'banner_name' => ['required', 'between:1,255', $unique],
            'banner_position' => ['required', 'max:1'],
            'banner_description' => ['nullable', 'between:1,255'],
            'banner_width' => ['nullable', 'max:9999'],
            'banner_height' => ['nullable', 'max:9999'],
            'banner_coin_price' => ['required', 'max:99999999'],
            'banner_price' => ['required', 'max:99999999'],
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
