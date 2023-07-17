<?php

namespace App\Http\Requests\Admin\Classified;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassifiedUpdateRequest extends FormRequest
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
        if($this->group_parent =="2"){
            $validate = [
                'title'=>['required','between:3,100',Rule::unique('classified','classified_name')->ignore($this->route('id'))],
                'content'=>['required','between:50,600'],
                'video_url'=>['max:255'],
                'project'=>['integer','nullable'],
                'classified_area'=>['integer'], //diện tích
                'dientich'=>['integer','required'], // đơn vị
                'giaban'=>['integer','nullable'], // giá bán
                'donviban'=>['required','integer'], // đơn vị giá
                'phaply'=>['required','integer'],
                'phongngu'=>['required','integer'],
                'phongvesinh'=>['required','integer'],
                'huong'=>['required','integer'],
                'tinh'=>['required','integer'],
                'huyen'=>['required','integer'],
                'xa'=>['required','integer'],
                'duong'=>['required','between:3,255'],
                'group_id'=>['required','integer'],
                'tinhtrang'=>['required','integer'],
                'noithat'=>['integer','nullable'],
                'contact_name'=>['max:30'],
                'contact_phone'=>['max:15'],
                'contact_email'=>['max:50'],
                'contact_address'=>['max:50'],
                // meta
                'video_url' => 'url|max:255',
                'meta_title' => 'max:255',
                'meta_key' => 'max:255',
                'meta_desc' => 'max:255',
            ];
            return $validate;
        }
        else
        if($this->group_parent == "10"){
            $validate = [
                'title'=>['required','between:3,100'],
                'content'=>['required','between:50,600'],
                'video_url'=>['max:255'],
                'project'=>['integer','nullable'],
                'classified_area'=>['integer'], //diện tích
                'dientich'=>['integer','required'], // đơn vị
                'giaban'=>['integer','nullable'], // giá bán
                'donviban'=>['required','integer'], // đơn vị giá
                'phaply'=>['required','integer'],
                'phongngu'=>['required','integer'],
                'phongvesinh'=>['required','integer'],
                'coctruoc'=>['required','integer'],
                'nguoitoida'=>['required','integer'],
                'huong'=>['required','integer'],
                'tinh'=>['required','integer'],
                'huyen'=>['required','integer'],
                'xa'=>['required','integer'],
                'duong'=>['required','between:3,255'],
                'group_id'=>['required','integer'],
                'tinhtrang'=>['required','integer'],
                'noithat'=>['integer','nullable'],
                'contact_name'=>['max:30'],
                'contact_phone'=>['max:15'],
                'contact_email'=>['max:50'],
                'contact_address'=>['max:50'],
            ];
            return $validate;
        }
        else {
            $validate = [
                'title'=>['required','between:3,100'],
                'content'=>['required','between:50,600'],
                'video_url'=>['max:255'],
                'project'=>['integer','nullable'],
                'classified_area'=>['integer'], //diện tích
                'dientich'=>['integer','required'], // đơn vị
                'giaban'=>['integer','nullable'], // giá bán
                'donviban'=>['required','integer'], // đơn vị giá
                'phaply'=>['required','integer'],
                'phongngu'=>['required','integer'],
                'phongvesinh'=>['required','integer'],
                'huong'=>['required','integer'],
                'tinh'=>['required','integer'],
                'huyen'=>['required','integer'],
                'xa'=>['required','integer'],
                'duong'=>['required','between:3,255'],
                'group_id'=>['required','integer'],
                'tinhtrang'=>['required','integer'],
                'noithat'=>['integer','nullable'],
                'contact_name'=>['max:30'],
                'contact_phone'=>['max:15'],
                'contact_email'=>['max:50'],
                'contact_address'=>['max:50'],
            ];
            return $validate;
        }
    }
    public function messages()
    {
        return config('constants.classified.validate_message');
    }
    public function attributes()
    {
        return config('constants.classified.validate_attribute_alias');
    }
}
