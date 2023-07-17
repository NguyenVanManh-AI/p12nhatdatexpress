<?php

namespace App\Http\Controllers\User;

use App\Helpers\SystemConfig;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Customer\CreateCustomerRequest;
use App\Models\District;
use App\Models\User\CustomerParam;
use App\Models\User\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\User\Customer\UpdateCustomerRequest;
use App\Http\Requests\User\Customer\UpdateNoteRequest;
use App\Services\CustomerService;

class CustomerController extends Controller
{
    private CustomerService $customerService;

    public function __construct()
    {
        $this->customerService = new CustomerService;
    }

    #Danh sach khach hang
    public function index(Request $request)
    {
        $user = Auth::guard('user')->user();
        $params = [
            'provinces' =>Province::get(),
            'districts' => District::where('province_id', $request->province)->get(),
            'sources' => CustomerParam::select('id', 'param_name')->where('param_type', 'CF')->get(),
            'status' => CustomerParam::select('id', 'param_name')->where('param_type', 'CS')->get(),
            'jobs' => CustomerParam::select('id', 'param_name')->where('param_type', 'JB')->get(),
            'customers' => Customer::list($request)->paginate(20)
        ];

        return View::make('user.customer.index', $params);
    }

    /**
     * Tao khach hang
     * @param CreateCustomerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_customer(CreateCustomerRequest $request)
    {
        $user = Auth::guard('user')->user();

        $checkLimitCustomer = $this->customerService->checkLimitCustomers($user);

        if ($checkLimitCustomer && !data_get($checkLimitCustomer, 'success')) {
            Toastr::error(data_get($checkLimitCustomer, 'message'));
            return back();
        }

        $customer_data = [
            'user_id' => $user->id,
            'fullname' => $request->fullname,
            'phone_number' => $request->phone_number,
            'email' => $request->email ,
            'birthday' => strtotime(($request->birthday)) ,
            'job' => $request->job ,
            'cus_status' => $request->status ,
            'cus_source' => $request->source,
            'note' => $request->note,
            'created_at' => time()
        ];

        if ($request->avatar) {
            $imagePath = base64ToFile($request->avatar, SystemConfig::userDirectory());
            $customer_data['image_url'] = $imagePath;
        }

        DB::beginTransaction();
        try {
            $customerId = DB::table('customer')->insertGetId($customer_data);
            $customer_location = [
                'cus_id' => $customerId,
                'address' => $request->address,
                'province_id' => $request->province,
                'district_id' => $request->district
            ];
            DB::table('customer_location')->insert($customer_location);

            DB::commit();
            Toastr::success("Tạo khách hàng thành công");
        }
        catch (\Exception $exception) {
            DB::rollBack();
            Toastr::error("Tạo khách hàng không thành công");
        }
        finally {
            return redirect()->back();
        }
    }

    /**
     * Cap nhat khach hang
     * @param UpdateCustomerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_customer(UpdateCustomerRequest $request)
    {
        $user = Auth::guard('user')->user();
        $customer = $this->customerService->find($user, $request->id);

        if (!$customer) {
            Toastr::error('Khách hàng không tồn tại!');
            return redirect()->back();
        }

        $customerData = $request->all();
        $this->customerService->updateCustomer($customer, $customerData);

        Toastr::success("Cập nhật khách hàng thành công");
        return redirect()->back();
    }

    #Xoa khach hang
    public function delete_customer($id)
    {
        $user = Auth::guard('user')->user();
        $customer = $this->customerService->find($user, $id);

        if (!$customer) {
            Toastr::error('Khách hàng không tồn tại!');
            return redirect()->back();
        }

        $customer->delete();

        Toastr::success('Xóa khách hàng thành công');
        return redirect()->back();
    }

    public function updateNote(UpdateNoteRequest $request, $id)
    {
        $user = Auth::guard('user')->user();
        $customer = $this->customerService->find($user, $id);

        if (!$customer) {
            Toastr::error('Khách hàng không tồn tại!');
            return redirect()->back();
        }

        $customer->update([
            'note' => $request->note
        ]);

        Toastr::success('Cập nhật thành công');
        return redirect()->back();
    }
}
