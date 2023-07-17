<?php

namespace App\Http\Requests\Admin\Rank;

use Illuminate\Foundation\Http\FormRequest;

class AddRankRequest extends FormRequest
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
        
        $this->merge(['deposit_min_amount' => intval(preg_replace('/\.|VND/', '', $this->deposit_min_amount))]);
        $this->merge(['deposit_max_amount' => intval(preg_replace('/\.|VND/', '', $this->deposit_max_amount))]);
        // dd($this->request->all() );
        $validate_array = [
            'level_name'=>['required','min:1','max:255'],
            'percent_special' =>['required','integer','min:1','max:100'],
            'classified_min_quantity' => ['required','integer','min:0','max:10000'],
            'classified_max_quantity' => ['required','integer','min:0','max:10000'],
            'deposit_min_amount'=>['required','integer','min:0','max:2000000000000'],
            'deposit_max_amount'=>['required','integer','min:0','max:2000000000000'],
            'image_url'=>['required','min:1','max:255']
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
