<?php

namespace App\Http\Requests\User\Index;

use App\Http\Requests\BaseRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserInfoRequest extends BaseRequest
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

    public function rules(Request $request)
    {
        $user = Auth::guard('user')->user();
        $rules =  [
            'fullname' => ['required', 'min:5', 'max:254'],
            'birthday' => ['required', 'date','date_format:Y-m-d'],
            'phone_number' => ['required','regex:/[0-9]/','min:8', 'max:11',
                Rule::unique('user','phone_number')->ignore($user->id, 'id')],
            'email' => ['required', 'email','max:254',
                Rule::unique('user','email')->ignore($user->id, 'id')],
            // 'tax_number' => ['required','regex:/[0-9]/','min:8', 'max:20',
            //     Rule::unique('user_detail','tax_number')->ignore($user->id, 'user_id')],
            'province_id' => ['required','exists:province,id'],
            'district_id' =>  ['required','exists:district,id'],
            'ward_id' =>  ['required','exists:ward,id'],
            'intro' => ['nullable', 'max:1000'],
            'website' => ['nullable', 'url']
        ];

        if (!data_get($user->detail, 'tax_number')) {
            $rules['tax_number'] = [
                'required',
                'regex:/[0-9]/',
                'between:8,20',
                Rule::unique('user_detail', 'tax_number')->ignore($user->id, 'user_id')
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = config('constants.validate_attribute_alias',[]);
        $user = Auth::guard('user')->user();
        $attributes['tax_number'] = $user->isEnterprise() ? 'Mã số thuế' : 'CMND/CCCD';

        return $attributes;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Toastr::error('Cập nhật không thành công');
        return parent::failedValidation($validator);
    }
}
