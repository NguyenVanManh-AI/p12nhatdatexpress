<?php

namespace App\Http\Requests\User\Classified;

use App\Http\Requests\BaseRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rules\CheckBlockedKeyWord;

class EditClassifiedRequest extends BaseRequest
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
        if ($request->group == 18) {
            $group = DB::table('group')
                ->where('parent_id', 18)
                ->where('id', $request->classified_type)->first();
        }
        else {
            $group = DB::table('group')->where('id', $request->group)->first();
        }

        $validate_array =  [
            'project' => ['nullable','exists:project,id'],
            'title' => [new CheckBlockedKeyWord(),'required', 'min:1', 'max:99'],
            'description' => [new CheckBlockedKeyWord(),'required', 'min:1', 'max:10000'],
            'area' => ['required','numeric', 'min:0.000001', 'max:10000000'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:10000000000000'],
            'paradigm' => ['required',"exists:group,id,parent_id,$group->id"],
            "bedroom" => ['nullable',"exists:classified_param,id,param_type,B"],
            "toilet" => ['nullable',"exists:classified_param,id,param_type,T"],
            "direction" => ['nullable',"exists:direction,id"],
            'address' => ['required', 'min:1', 'max:254'],
            'province' => ['required','exists:province,id'],
            'district' =>  ['required',"exists:district,id,province_id,$request->province"],
            // 'ward' =>  ['required',"exists:ward,id,district_id, $request->district"],
            'video_url' => ['nullable','url', 'min:1', 'max:254'],
            'date_from' => ['nullable', 'date','date_format:Y-m-d'],
            'date_to' => ['nullable', 'date','date_format:Y-m-d','after_or_equal:date_from'],
            // 'meta_title' => [new CheckBlockedKeyWord(),'required', 'min:1', 'max:254'],
            'meta_key' => ['nullable', 'min:1', 'max:254'],
            'is_monopoly' => ['nullable', 'in:1'],
            // 'meta_desc' => [new CheckBlockedKeyWord(),'required', 'min:1', 'max:10000'],
            'is_monopoly' => ['nullable', 'in:1'],
            'is_broker' => ['nullable', 'in:1'],
            'g-recaptcha-response' => 'required|captcha',
        ];

        if ($group->parent_id != 18) {
            $validate_array['progress'] =['required',"exists:progress,id,group_id,$request->paradigm"];
            // $validate_array['progress'] = ['required', "exists:progress,id,group_id,$request->paradigm"];
        }

        #Hinh anhr tin dang
        if ($request->gallery_project) {
            $validate_array['gallery'] = ['nullable'];
        }
        else {
            $validate_array['gallery'] = ['required'];
        }

        if ($group->id == 2)
        {
            $validate_array['unit_price'] = ['required', 'in:1,2,3,4'];
            $validate_array['juridical'] = ['required',"exists:classified_param,id,param_type,L"];
            $validate_array['furniture'] =['nullable',"exists:furniture,id,group_id,$request->paradigm"];
        }

        if ($group->id == 10 || $group->id == 19 || $group->id == 20 )
        {
            $validate_array['unit_price'] = ['required', 'in:1,3,4,5,6'];
            $validate_array['freezer'] = ['nullable', 'in:1'];
            $validate_array['balcony'] = ['nullable', 'in:1'];
            $validate_array['internet'] = ['nullable', 'in:1'];
            $validate_array['mezzanino'] = ['nullable', 'in:1'];

        }

        if ($group->id == 10)
        {
            $validate_array['deposit'] = ['required',"exists:classified_param,id,param_type,A"];
            $validate_array['capacity'] =  ['required',"exists:classified_param,id,param_type,P"];
        }

        return $validate_array;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Toastr::error('Cập nhật tin đăng không thành công!');
        return parent::failedValidation($validator);
    }
}
