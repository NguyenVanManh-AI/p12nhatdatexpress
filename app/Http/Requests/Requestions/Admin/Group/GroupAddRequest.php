<?php

namespace App\Http\Requests\Requestions\Admin\Group;

use Illuminate\Foundation\Http\FormRequest;

class GroupAddRequest extends FormRequest
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
            'group_name' => 'required|between:3,100|unique:group,group_name',
            // 'image.url'=>['image','between:1,5120'],
            'image_url' => 'max:255',
            'group_url' => 'required|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:group,group_url',
            'group_description' => 'max:50000',
        ];
    }

    public function messages()
    {
        return config('constants.group.validate_message');
    }

    public function attributes()
    {
        return config('constants.group.validate_attribute_alias');
    }
}
