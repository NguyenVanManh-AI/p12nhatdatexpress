<?php

namespace App\Http\Requests\User\Express;

use App\Http\Requests\BaseRequest;
use App\Models\Banner\Banner;
use App\Models\Banner\BannerGroup;
use App\Models\Group;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class AddExpressRequest extends BaseRequest
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
        return  [
            'banner_group' => ['required', 'in:H,C'],
            'banner_type' => ['required', 'in:D,M'],
            'banner_position' => ['required', 'in:L,R,C'],
            'category' => 'required_if:banner_group,C|nullable|exists:group,id',
            'date_from' => ['required', 'date','date_format:m/d/Y','after_or_equal:today'],
            'date_to' => ['required', 'date','date_format:m/d/Y','after_or_equal:date_from'],
            // 'banner_image' => ['required','image','mimes:jpeg,jpg,png'],
            'banner_image' => ['required'],
            // 'select_banner_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg,webp|max:22',
            'voucher' => ['nullable', 'min:4', 'max:5'],
            'paradigm' => ['nullable','exists:group,id'],
            'banner_link' => ['nullable','url', 'between:1,255'],
            'banner_date' => [
                function ($attribute, $value, $fail) {
                    $date_from = strtotime($this->date_from);
                    $date_to = strtotime($this->date_to) + 86399;
                    $isChild = false;

                    if ($this->banner_group == 'H') {
                        // home page
                        $group = Group::firstWhere('group_url', 'trang-chu');
                        $groupId = data_get($group, 'id', 1);
                    } else {
                        // paradigm page
                        $category = Group::find($this->category);
                        $paradigm = Group::find($this->paradigm);

                        $groupId = data_get($paradigm, 'id', data_get($category, 'id', 1));
                        $isChild = $paradigm ? true : false;
                    }

                    $banner_group = BannerGroup::where('banner_group', $this->banner_group)
                        ->where('banner_permission', 1)
                        ->where('banner_position', $this->banner_position)
                        ->where('is_child', $isChild)
                        ->firstWhere('banner_type', $this->banner_type);

                    $banner = Banner::where('group_id', $groupId)
                        ->where('banner_group_id', data_get($banner_group, 'id'))
                        ->where('date_to', '>=', $date_from)
                        ->where('date_from', '<=', $date_to)
                        ->firstWhere('is_deleted', 0);

                    if ($banner) {
                        return $fail('Ngày chiến dịch không khả dụng');
                    }
                }
            ]
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Toastr::error($validator->messages()->first());

        return parent::failedValidation($validator);
    }
}
