<?php

namespace App\Http\Requests\Requestions\Admin\Group;


use Illuminate\Foundation\Http\FormRequest;
use App\Models\Group;

class GroupUpdateRequest extends FormRequest
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
        $group = Group::find($this->id);
        return [
            'group_name' => [
                'required',
                'between:3, 100',
                function ($attribute, $value, $fail) use ($group) {
                    $duplicate = Group::where('parent_id', $group->parent_id)
                        ->where('id', '!=', $group->id)
                        ->firstWhere('group_name', $this->group_name);
                    if ($duplicate) {
                        return $fail('Tên danh mục đã tồn tại');
                    }
                }
            ],
            // 'image.url'=>['image','between:1,5120'],
            'image_url' => 'max:255',
            'group_url' => [
                'required',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                function ($attribute, $value, $fail) use ($group) {
                    $duplicate = Group::where('parent_id', $group->parent_id)
                        ->where('id', '!=', $group->id)
                        ->firstWhere('group_url', $this->group_url);
                    if ($duplicate) {
                        return $fail('Đường dẫn tĩnh đã tồn tại');
                    }
                }
            ],
            'group_description' => 'max:50000',
            'dependencies' => 'array',
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
