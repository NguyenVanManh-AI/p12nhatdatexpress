<?php

namespace App\Http\Requests\Admin\Project;

use Illuminate\Foundation\Http\FormRequest;

class EditPropertiesRequest extends FormRequest
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
        $validate_array =  [
            'tenduan' => ['required','min:3', 'max:100'],
            'giaban' => ['required','min:3', 'max:100'],
            'huongnha' => ['required','min:3', 'max:100'],
            'mohinh' => ['required','min:3', 'max:100'],
            'giathue' => ['required','min:3', 'max:100'],
            'quymo' => ['required','min:3', 'max:100'],
            'chudautu' => ['required','min:3', 'max:100'],
            'dientich' => ['required','min:3', 'max:100'],
            'hotrovay' => ['required','min:3', 'max:100'],
            'vitri' => ['required','min:3', 'max:100'],
            'phaply' => ['required','min:3', 'max:100'],
            'duong' => ['required','min:3', 'max:100'],
            'tinhtrang' => ['required','min:3', 'max:100'],
            'noithat' => ['required','min:3', 'max:100'],
            'dangngay' => ['required','min:3', 'max:100'],

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
