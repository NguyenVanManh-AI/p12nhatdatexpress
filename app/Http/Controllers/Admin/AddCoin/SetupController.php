<?php

namespace App\Http\Controllers\Admin\AddCoin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Payment\UpdatePaymentRequest;
use App\Models\AdminConfig;
use App\Models\PaymentMethod;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SetupController extends Controller
{
    public function get_attr(){

        $ids = ['N004', 'N005','N014'];
        $notes = AdminConfig::whereIn('config_code', $ids)->get();
        $bank_payments = PaymentMethod::where('payment_name', 'like', 'NH%')->get();
        $bank_payments->map(function ($payment){
            $payment->payment_param = unserialize($payment->payment_param);
        });
        $momo_payments = PaymentMethod::where('payment_name', 'like', 'MOMO%')->get();
        $momo_payments->map(function ($payment){
            $payment->payment_param = unserialize($payment->payment_param);
        });
        $zalo_pay_payment = PaymentMethod::where('payment_name', 'like', 'ZALOPAY%')->get();
        $zalo_pay_payment->map(function ($payment){
            $payment->payment_param = unserialize($payment->payment_param);
        });

        return view('Admin.AddCoin.SettingCoin',compact("notes", "bank_payments", "zalo_pay_payment", "momo_payments"));
    }
    public function post_attr(Request $request){
        $ids = ['N004', 'N005','N014'];
        foreach ($ids as $item){
            // update note
            $config = AdminConfig::firstWhere('config_code', $item);

            if (!$config) continue;

            $config->update([
                'config_value' =>  $request->$item ?? ''
            ]);
            $data[$item]=$request->$item ?? '';
        }
        // Helper::create_admin_log(61,$data);
        Toastr::success('Thành công');
        return back();
    }

    # Lưu method payment BANK
    public function update_payment(UpdatePaymentRequest $request){
        $paymentMethod = PaymentMethod::where('payment_name', $request->payment_name)->firstOrFail();
            $data = [
                'stk' => $request->stk,
                'nh' => $request->nh,
                'cn' => $request->cn,
                'ctk' => $request->ctk,
            ];
            // Helper::create_admin_log(62,$data);
            if ($request->qr) $data['qr'] = $request->qr;

            $paymentMethod->update([
                'payment_param' => serialize($data)
            ]);

            Toastr::success('Cập nhật phương thức thanh toán thành công');

        return redirect()->back();
    }
}
