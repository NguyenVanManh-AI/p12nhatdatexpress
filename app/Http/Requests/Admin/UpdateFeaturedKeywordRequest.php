<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Models\District;
use App\Models\FeaturedKeyword;
use App\Models\Group;

class UpdateFeaturedKeywordRequest extends BaseRequest
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
        $rules = [
            'target_type' => 'required',
            'paradigm_id' => [
                'required_if:target_type,' . Group::class,
                // 'exists:group,id',
                function ($attribute, $value, $fail) {
                    $unique = FeaturedKeyword::where('target_type', Group::class)
                        ->where('id', '!=', request()->keyword->id)
                        ->firstWhere('target_id', $value);

                    if ($unique) {
                        return $fail(':attribute đã tồn tại');
                    }
                }
            ],
            'district_id' => [
                'required_if:target_type,' . District::class,
                // 'exists:district,id',
                function ($attribute, $value, $fail) {
                    $unique = FeaturedKeyword::where('target_type', District::class)
                        ->where('id', '!=', request()->keyword->id)
                        ->firstWhere('target_id', $value);

                    if ($unique) {
                        return $fail(':attribute đã tồn tại');
                    }
                }
            ],
            'views' => 'required|numeric',
            'is_active' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = config('constants.validate_message', []);

        $messages['paradigm_id.required_if'] = ':attribute là bắt buộc khi :other là mô hình.';
        $messages['district_id.required_if'] = ':attribute là bắt buộc khi :other là quận/huyện.';

        return $messages;
    }
}
