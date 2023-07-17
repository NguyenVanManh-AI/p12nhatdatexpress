<?php

namespace App\Http\Requests\Chat;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class CreateMessage extends FormRequest
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
        $validate_array = [
            'chat_message' => ['required', 'between:1,254'],
            'chat_code' => ['required', 'between:1,49'],
        ];
        if ($request->type){
            $validate_array['user_id'] = ['required', 'exists:user,id'];
        }else{
            $validate_array['admin_id'] = ['required', 'exists:admin,id'];
        }
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
//        Toastr::error('Gửi tin nhắn thất bại');
//        return parent::failedValidation($validator);
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
