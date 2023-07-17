<?php

namespace App\Http\Requests\User\Customer;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
        $validate_array =  [
            'fullname' => ['required', 'min:8', 'max:254'],
            'phone_number' => ['required','regex:/[0-9]/','min:8', 'max:11',
                Rule::unique('customer','phone_number')
                    ->where('is_deleted', 0)
                    ->ignore( $request->id, 'id')],
            'avatar_url' => ['nullable'],
            'address' => ['required', 'min:1', 'max:254'],
            'province' => ['required','exists:province,id'],
            'district' =>  ['required','exists:district,id'],
            'email' => ['nullable', 'email','max:254',
                Rule::unique('customer','email')
                    ->where('is_deleted', 0)
                    ->ignore($request->id, 'id')],
            'birthday' => ['required', 'date','date_format:Y-m-d'],
            'job' => ['required','exists:customer_param,id'],
            'source' => ['required','exists:customer_param,id'],
            'status' => ['required','exists:customer_param,id'],
            'note' => ['nullable', 'max:500']

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
        Session::flash('popup', '#edit-customer');
        Toastr::error('Cập nhật không thành công');
        return parent::failedValidation($validator);
    }
}
