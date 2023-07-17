<?php

namespace App\Http\Requests\Home\Project;

use App\Http\Requests\BaseRequest;
use App\Rules\CheckBlockedUser;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;

class ProjectReportRequest extends BaseRequest
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
            'report_type' => [
                new CheckBlockedUser('báo cáo'),
                'required',
                'exists:report_group,id'
            ],
            'report_content' => 'max:200',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Session::flash('popup_display', '#report_content');
        Session::flash('popup_action', route('home.project.report-content', $this->project_id));
        Toastr::error('Báo cáo không thành công');
        return parent::failedValidation($validator);
    }
}
