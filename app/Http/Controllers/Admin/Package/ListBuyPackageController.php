<?php

namespace App\Http\Controllers\Admin\Package;

use App\Http\Controllers\Controller;
use App\Models\ClassifiedPackage;
use App\Models\PaymentMethod;
use App\Models\User\UserDeposit;
use App\Models\User\UserTransaction;
use App\Models\UserBalance;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListBuyPackageController extends Controller
{
    public function list_buy( Request $request)
    {
        $itemsPerPage = $request->items ?: 10;
        $page = $request->page ?: 1;

        // $items = 10;
        // if ($request->has('items')  && is_numeric($request->items)) {
        //     $items = $request->items;
        // }
        // get data gói tin
        $package = ClassifiedPackage::get();
        // get data hình thức thanh toán
        $payment = PaymentMethod::get();

        $admin_role_id = Auth::guard('admin')->user()->rol_id; // 1. lấy id role
        $list_buy = UserTransaction::query()
                    ->join('user','user_transaction.user_id','=','user.id')
                    ->join('user_detail','user.id','=','user_detail.user_id')
                    ->leftJoin('user_deposit','user_transaction.id','=','user_deposit.user_transaction_id')
                    ->leftJoin('classified_package','user_transaction.post_package_id','=','classified_package.id')
                    ->leftJoin('payment_method','user_deposit.payment_method_id','=','payment_method.id')
                    ->select(
                        'user_transaction.id','user_transaction.created_at','user_detail.fullname',
                        'user.email','user.phone_number','user_deposit.deposit_code',
                        'user_deposit.deposit_amount','user_deposit.deposit_status',
                        'user_transaction.coin_amount',
                        'user_deposit.id as deposit_id',
                        'classified_package.package_name',
                        'payment_method.payment_name','user_deposit.payment_method_id',
                        'user_transaction.post_package_id'
                    )
                    ->where('user_transaction.transaction_type', 'I');

        // lọc theo tình trạng
        if(isset($request->package_status)&&$request->package_status!="") $list_buy = $list_buy->where('deposit_status',$request->package_status);
        // lọc theo gói tin
        if(isset($request->classified_package)&&$request->classified_package!="") $list_buy = $list_buy->where('post_package_id',$request->classified_package);
        // lọc theo hình thức thanh toán
        // if(isset($request->payment)&&$request->payment!="") $list_buy = $list_buy->where('payment_method_id',$request->payment);
        if ($request->payment) {
            if ($request->payment != 'coin') {
                $list_buy = $list_buy->where('payment_method_id', $request->payment);
            } else {
                $list_buy = $list_buy->whereNotNull('user_transaction.coin_amount');
            }
        }

        // lọc theo từ khóa
        if(!empty($request->keyword)) {
            $keyWord = $request->keyword;
            $list_buy->where(function ($query) use ($keyWord) {
                return $query->where('user_detail.fullname', 'LIKE', '%' . $keyWord . '%')
                    ->orWhere('user_deposit.deposit_code', 'LIKE', '%' . $keyWord . '%')
                    ->orWhere('user.phone_number', 'LIKE', '%' . $keyWord . '%');
            });
        }
        if(!empty($request->start_day) || !empty($request->end_day)){
            $list_buy= $list_buy->whereBetween('user_transaction.created_at', [strtotime($request->start_day),strtotime($request->end_day)]);
        }
        // $list_buy =  CollectionHelper::paginate($list_buy,$items);

        $list_buy = $list_buy->latest()
            ->distinct()
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return view('Admin.Package.ListBuyPackage',compact('list_buy','package','payment'));
    }

    // thay đổi trạng thái
    public function change_status($status, $id)
    {
        $package = UserDeposit::findOrFail($id);
        // if ($package == null){
        //     Toastr::error("Không tồn tại giao dịch");
        //     return back();
        // }

        // // if($status !=3 ){
        //     DB::table('user_deposit')
        //         ->where('id',$id)
        //         ->update([
        //             'deposit_status' => $status,
        //             'confirm_by'=>Auth::guard('admin')->user()->id,
        //             'is_confirm'=>1,
        //             'confirm_time' =>strtotime(Carbon::now())
        //         ]);

        $confirmBy = Auth::guard('admin')->user()->id;

        $package->update([
            'deposit_status' => $status,
            'confirm_by'=> $confirmBy,
            'is_confirm'=> 1,
            'confirm_time' => time(),
            'one_time_confirm_token' => null
        ]);

        if ($status == 1) {
            $userBalance = UserBalance::where('user_id', $package->user_id)
            ->firstWhere('package_id', '!=', 1);

            if ($userBalance) {
                $userBalance->update([
                    'status' => 0
                ]);
            }
           
            $transactionBalance = UserBalance::where('transaction_id', $package->user_transaction_id)
                ->firstWhere('package_id', '!=', 1);
            if ($transactionBalance) {
                $transactionBalance->update([
                    'status' => 0
                ]);
            }
        }

        // $data = [
        //     'id' => $id,
        //     'deposit_status' => $status,
        //     'confirm_by' => $confirmBy,
        //     'is_confirm' => 1,
        //     'confirm_time' => time()
        // ];

        // Helper::create_admin_log(115, $data);

        Toastr::success("Cập nhật trạng thái thành công");
        return back();
    }

}
