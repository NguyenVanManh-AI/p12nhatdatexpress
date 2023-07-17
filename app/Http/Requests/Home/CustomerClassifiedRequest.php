<?php

namespace App\Http\Requests\Home;

use Illuminate\Foundation\Http\FormRequest;

class CustomerClassifiedRequest extends FormRequest
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
            'fullname' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|max:255',
            'classified_id' => 'required|exists:classified,id',
        ];
    }

    public function messages()
    {
        return config('constants.validate_message', []);
    }

    // protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     // Toastr::error($validator->errors()->first());
    //     return parent::failedValidation($validator);
    // }
}
