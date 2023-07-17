<?php

namespace App\Http\Requests\User\Index;

use App\Http\Requests\BaseRequest;

class UpdateDeployingProject extends BaseRequest
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
        // dd(request()->all());
        return  [
            'projects' => 'required|array|max:5',
        ];
    }
}
