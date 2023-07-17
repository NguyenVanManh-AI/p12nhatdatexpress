<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Http\Controllers\Controller;
use App\Models\AdminMailTemplate;
use App\Models\Province;
use App\Models\User\Customer;
use App\Models\User\CustomerParam;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    # List contact
    public function list_contact(Request $request){
        $items = $request->items ?? 10;

        $job = CustomerParam::where(['param_type' => 'JB'])->get();
        $province = Province::showed()->get();
        $list_type = CustomerParam::where('param_type', 'CF')->get();

        $contact = $this->get_customer($request)
            ->select('province.province_name', 'province.id as province_id', 'district.district_name', 'district.id as district_id', 'customer.*', 'customer_param.param_name', 'customer_param.id as job_id', 'customer_param1.param_name as list_type_name', 'customer_param1.id as list_type_id', 'group.group_name', 'group.id as group_id')
            ->paginate($items);

        $templateEmail = AdminMailTemplate::select('id', 'template_title')->get();

        return view('Admin.Contact.ContactManage', compact('contact', 'job', 'province', 'list_type', 'templateEmail'));
    }

    # Get user
    public function get_customer(Request $request): Builder
    {
        $contact = Customer::query()
            ->join('customer_param', 'customer.job', '=', 'customer_param.id')
            ->leftJoin('classified', 'customer.classified_id', '=', 'classified.id')
            ->leftJoin('customer_param as customer_param1', 'customer.cus_source', '=', 'customer_param1.id')
            ->join('group', 'classified.group_id', '=', 'group.id')
            ->leftJoin('customer_location', 'customer.id', '=', 'customer_location.cus_id')
            ->leftJoin('province', 'customer_location.province_id', '=', 'province.id')
            ->leftJoin('district', 'customer_location.district_id', '=', 'district.id');


        if (isset($request->job) && $request->job != "") {
            $contact = $contact->where('job', $request->job);
        }

        if (isset($request->province) && $request->province != "") {
            $contact = $contact->where('customer_location.province_id', $request->province);
        }

        if (isset($request->district) && $request->district != "") {
            $contact = $contact->where('district_id', $request->district);
        }
        if (isset($request->birthday) && $request->birthday != "") {
            $midnight = strtotime($request->birthday, mktime(0,0,0));
            $contact = $contact->whereBetween('birthday', [ $midnight , $midnight + 86399 ]);
        }

        if (isset($request->created_at) && $request->created_at != "") {
            $midnight = strtotime($request->created_at, mktime(0,0,0));
            $contact = $contact->whereBetween('customer.created_at', [ $midnight , $midnight + 86399 ]);
        }

        if (isset($request->list_type) && $request->list_type != "") {
            $contact = $contact->where('cus_source', $request->list_type);
        }

        if (isset($request->group_child) && $request->group_child != "") {
            $contact = $contact->where('group_id', $request->group_child);
        } else if (isset($request->group_id) && $request->group_id != "") {
            $contact = $contact->where('parent_id', $request->group_id);
        }

        return $contact;
    }
}
