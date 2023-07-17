<?php

namespace App\Http\Requests\User\Mail;

use App\Http\Requests\BaseRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EditCampaignRequest extends BaseRequest
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
        $rules = [
            'campaign_name' => [
                'required',
                'max:255',
                Rule::unique('user_mail_campaign', 'campaign_name')
                    ->where('is_deleted', false)
                    ->ignore($this->id, 'id')
            ],
            'mail_template_id' => 'required|exists:user_mail_template,id',
            'start_date' => 'nullable|date',
            'customers' => 'array',
        ];

        if (!$this->customers || $this->customers && !count($this->customers)) {
            $rules['province_id'] = 'nullable|exists:province,id';
            $rules['date_from'] = 'nullable|date|date_format:Y-m-d';
            $rules['date_to'] = 'nullable|date|date_format:Y-m-d';
            $rules['cus_job'] = 'nullable|exists:customer_param,id';
            $rules['cus_source'] = 'nullable|exists:customer_param,id';
            $rules['cus_status'] = 'nullable|exists:customer_param,id';
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = config('constants.validate_attribute_alias');
        $attributes['province_id'] = 'khu vực';

        return $attributes;
    }
}
