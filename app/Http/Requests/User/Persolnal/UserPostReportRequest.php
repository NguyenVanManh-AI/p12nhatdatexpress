<?php

namespace App\Http\Requests\User\Persolnal;

use App\Http\Requests\BaseRequest;
use App\Rules\CheckBlockedUser;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;

class UserPostReportRequest extends BaseRequest
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
        Session::flash('popup_action', route('trang-ca-nhan.report-content', $this->post_id));
        Toastr::error('Báo cáo không thành công');
        return parent::failedValidation($validator);
    }
}
