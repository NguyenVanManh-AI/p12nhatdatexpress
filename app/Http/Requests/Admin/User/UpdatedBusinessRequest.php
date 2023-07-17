<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\BaseRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class UpdatedBusinessRequest extends BaseRequest
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
            'password' => 'nullable|min:6|confirmed',
            'tax_number' => [
                'nullable',
                'regex:/[0-9]/',
                'between:8,20',
                Rule::unique('user_detail', 'tax_number')->ignore($this->id, 'user_id')
            ]
        ];
    }
    
    public function attributes()
    {
        $attributes = config('constants.validate_attribute_alias',[]);
        $user = User::find($this->id);
        $attributes['tax_number'] = data_get($user, 'user_type_id') == 3 ? 'Mã số thuế' : 'CMND/CCCD';

        return $attributes;
    }
}
