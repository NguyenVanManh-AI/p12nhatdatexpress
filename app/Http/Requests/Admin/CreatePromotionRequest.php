<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class CreatePromotionRequest extends BaseRequest
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
            'quanlity_code' => 'required|integer|between:1,200',
            'promotion_type' => 'required|boolean',
            'num_use' => 'required|integer|between:1,10000',
            'value' => 'required|integer|between:1,100',
        ];
    }
}
