<?php

namespace App\Http\Controllers\Admin\AddCoin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Bill\UploadBillRequest;
use App\Models\User\BillService;
use App\Models\User\UserDeposit;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use File;

class ExportBillController extends Controller
{
    public function bill(Request $request){
        $items = 10;
        if ($request->has('items')  && is_numeric($request->items)) {
            $items = $request->items;
        };
        $params = Helper::array_remove_null($request->all());

        $list_buy = UserDeposit::with('user_detail')
            ->select('user_deposit.*',
                'b.id as bill_id', 'b.bill_type', 'b.company_name', 'b.company_representative', 'b.tax_code', 'b.company_address', 'b.bill_note', 'b.confirm_status', 'b.bill_url', 'b.created_at')
            ->leftJoin('bill_service as b', 'b.transaction_id', '=', 'user_transaction_id')
            ->with('user.user_location.province')
            ->with('user.user_location.district')
            ->with('user.user_location.ward')
            ->with('user_detail')
            ->where('deposit_type','=', 'I')
            ->orWhere('deposit_type','=', 'C')
            ->orderBy('b.created_at', 'desc')
            ->orderBy('deposit_time', 'desc')
            ->filter($params)
            ->paginate($items);

        $list_buy = BillService::with('user', 'user_detail', 'transaction')
            ->orderBy('id', 'desc')
            ->filter($params)->paginate($items);
        return view('Admin.AddCoin.ExportBill', compact('list_buy'));
    }

    public function upload_bill(UploadBillRequest $request, $id){
        $bill = BillService::findOrFail($id);
         
            if ($request->hasFile('file')) {
                if ($bill->bill_url){
                    File::delete($bill->bill_url);
                }
                $name = time() . Str::random() . '.' . $request->file->getClientOriginalExtension();
                $request->file->move(public_path('uploads/admin/bill/'), $name);
                $bill_url = 'uploads/admin/bill/' . $name;
                $bill->update([
                    'confirm_status' => 1,
                    'confirm_by' => Auth::guard('admin')->id(),
                    'confirm_time' => time(),
                    'bill_url' => $bill_url
                ]);
                $data =[
                    'id'=>$id,
                    'confirm_status' => 1,
                    'confirm_by' => Auth::guard('admin')->id(),
                    'confirm_time' => time(),
                    'bill_url' => $bill_url
                ];
                // Helper::create_admin_log(66,$data);
                Toastr::success("Upload hóa đơn thành công");
                return back();
            }
            else {
                Toastr::error("Vui lòng chọn file");
                return back();
            }
    }

}
