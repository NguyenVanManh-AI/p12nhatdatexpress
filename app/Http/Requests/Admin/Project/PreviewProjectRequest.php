<?php

namespace App\Http\Requests\Admin\Project;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PreviewProjectRequest extends FormRequest
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
        $url_unique =  'unique:project,project_url';
        if (array_key_exists('id', $this->validationData())){
           $url_unique = Rule::unique('project','project_url')->ignore($this->validationData()['id']);
        }
        $validate_array =  [
            'project_name' => ['required', 'between:3,100'],
            'group_id' => ['required', 'exists:group,id'],
            'investor' => ['nullable', 'between:3,255'],
            'project_description' => ['nullable', 'between:3,255'],
            'project_content' => ['required', 'between:3,50000'],
            'project_url' => ['required', 'between:3,255', $url_unique],
            'location_descritpion' => ['nullable', 'between:3,50000'],
            'utility_description' => ['nullable', 'between:3,50000'],
            'ground_description' => ['nullable', 'between:3,50000'],
            'legal_description' => ['nullable', 'between:3,50000'],
            'payment_description' => ['nullable', 'between:3,50000'],
            'project_area_from' => ['nullable', 'integer','max:999999'],
            'project_area_to' => ['required', 'integer','max:999999', $this->project_area_from ? 'gte:project_area_from' : ''],
            'project_scale' => ['nullable', 'integer', 'between:1,999999'],
            'project_price' => ['nullable', 'numeric', 'between:1,999999'],
            'project_rent_price' => ['nullable', 'numeric', 'between:1,999999'],
            'project_direction' => ['required', 'exists:direction,id'],
            'list_utility' => ['nullable', 'array'],
            'project_juridical' => ['nullable', 'exists:project_param,id'],
            'project_progress' => ['required', 'exists:progress,id'],
            'project_furniture' => ['nullable', 'exists:furniture,id'],
            'project_road' => ['nullable', 'integer', 'between:1,999999'],
            'bank_sponsor' => ['nullable'],
            'prepay_percent' => ['nullable', 'integer', 'between:1,100'],
            'loan_time' => ['nullable', 'integer', 'between:1,1200'],
            'interest_percent' => ['nullable', 'integer', 'between:1,100'],
            'video' => ['nullable', 'regex:/(https?\:\/\/)?(www\.youtube\.com|youtu\.be)\/embed\/.+/'],
            'meta_title' => ['required', 'between:3,255'],
            'meta_key' => ['nullable', 'between:1,255'],
            'meta_desc' => ['nullable', 'between:1,255'],
            'map_latitude' => ['required', 'between:1,255'],
            'map_longtitude' => ['required', 'between:1,255'],
            'province_id' => ['required', 'exists:province,id'],
            'district_id' => ['required', 'exists:district,id'],
            'ward_id' => ['required', 'exists:ward,id'],
            'address' => ['nullable', 'between:1,255'],
            'image_thumbnail' => ['required','between:1,255'],
            'image_url' => ['required']
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

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    /** Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        return $this->objectToArray($this->data);
    }

    /**
     * Convert object to array
     *
     * @param $input
     * @return array
     */
    public function objectToArray($input) : array
    {
        $new = [];
        foreach ($input as $item) {
            if (is_object($item) || is_array($item)) {
                $new[$item['name']] = $item['value'];
            }
        }
        return (array) $new;
    }
}
