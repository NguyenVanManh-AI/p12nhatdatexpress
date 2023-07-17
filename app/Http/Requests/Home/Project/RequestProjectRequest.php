<?php

namespace App\Http\Requests\Home\Project;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RequestProjectRequest extends FormRequest
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
    public function rules(Request $request)
    {

        $after_date = '01-01-1900';
        $current_date = date('Y-m-d',time());

        $validate_array =  [
            'project_name' => ['required', 'between:1,254'],
            'investor' => ['required', 'between:1,254'],
            'address' => ['required', 'between:1,254'],
            'ward_id' => ['required', 'exists:ward,id'],
            'district_id' => ['required', 'exists:district,id'],
            'province_id' => ['required', 'exists:province,id'],
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

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Toastr::error('Yêu cầu dự án không thành công');
        return parent::failedValidation($validator);
    }
}
