<?php

namespace App\Http\Controllers\User;

use App\CPU\Deposit;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Deposit\DepositRequest;
use App\Http\Requests\User\Deposit\InvoiceRequest;
use App\Models\User\BillService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\CPU\Voucher;
use File;
use Response;
use App\Models\User;
use App\Jobs\SendUserEmail;
use App\Models\User\UserDeposit;
use App\Helpers\Helper;
use App\Services\MailService;

class DepositController extends Controller
{

    private MailService $mailService;

    public function __construct()
    {
        $this->mailService = new MailService;
    }

    /**
     * Get deposit
     * @param string $voucher_code
     * @return \Illuminate\Contracts\View\View
     */
    public function index($voucher_code = null)
    {
        $user = Auth::guard('user')->user();
        #Check voucher valid
        $params['voucher_code'] = null;
        if ($voucher_code) {
            $checkVoucherStatus = Voucher::checkVoucherValid(1, $voucher_code);
            if ($checkVoucherStatus['status']) {
                $params['voucher_code'] = $voucher_code;
            } else {
                Toastr::error($checkVoucherStatus['message']);
            }
        }

        #get transaction code
        do {
            $transactionCode = random_string(10);
            $existCode = DB::table('user_deposit')->where('deposit_code', $transactionCode)->value('deposit_code');
        } while ($existCode);
        $params['transaction_code'] = $transactionCode;
        session(['deposit_code' => $params['transaction_code']]);

        #get payment method
        $payment_methods = DB::table('payment_method')->get();
        foreach ($payment_methods as $payment_method) {
            $params['payment_method'][$payment_method->payment_name] = unserialize($payment_method->payment_param);
        }

        #get deposit history
        $params['deposit_list'] = DB::table('user_deposit as ude')
            ->select('ude.deposit_time', 'ude.deposit_code', 'pme.payment_name', 'ude.deposit_amount',
                'utr.voucher_discount_percent', 'ude.deposit_status', DB::raw("ROW_NUMBER() over (order by ude.id)as num"))
            ->whereRaw("ude.user_id = $user->id and ude.deposit_type = 'C'  ")
            ->join('payment_method as pme', 'ude.payment_method_id', '=', 'pme.id')
            ->join('user_transaction as utr', 'ude.user_transaction_id', '=', 'utr.id')
            ->paginate(20);

        $params['total_coin_amount'] = DB::table('user')->where('id', $user->id)->value('coint_amount');
        $params['total_coin_ref'] = DB::table('user_coin_ref_receipt')->where('user_id', $user->id)->sum('receipt_coin');
        $params['guide'] = DB::table('user_guide')->where('id', 4)->value('guide_content');

        return View::make('user.deposit.index', $params);

    }

    /**
     * post deposit
     * @param DepositRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_deposit(DepositRequest $request)
    {
        $user = Auth::guard('user')->user();
        $one_time_confirm_token = md5(uniqid($user->email).date('d-m-Y H:i:s'));
        $depositStatus = Deposit::deposit($request->payment_method, $request->deposit_code, $request->deposit_amount, $request->deposit_voucher,$one_time_confirm_token);

        if($depositStatus['status']){
            self::sendActiveDeposit($user,$one_time_confirm_token);
            Toastr::success($depositStatus['message']);
        }
        else {
            Toastr::error($depositStatus['message']);
        }
        return redirect()->back();

    }
    public function sendActiveDeposit(User $user,$one_time_confirm_token)
    {
        $mailConfig = DB::table('admin_mail_config')->where('is_deleted', 0)->first();
        if ($mailConfig) {
            $mail_deposits = DB::table('system_config')->first()->mail_deposit;
            $emails = explode(",", $mail_deposits);
            foreach($emails as $email){
                $replaceData = [];
                $replaceData['one_time_confirm_token'] = $one_time_confirm_token;
                $replaceData['user'] = $user;
                $replaceData['email'] = $email;
                $mailTemplate = $this->mailService->getContent('duyet_nap_Express_Coin', $replaceData);
                SendUserEmail::dispatch($mailConfig->mail_host, $mailConfig->mail_port, $mailConfig->mail_encryption, $mailConfig->mail_username, $mailConfig->mail_password, $email, $mailConfig, $mailTemplate->title, $mailTemplate->template_content);
            }
        }
    }

    public function get_active_deposit($one_time_confirm_token)
    {
        $user_deposit = UserDeposit::where('one_time_confirm_token',$one_time_confirm_token)->first();
        if($user_deposit){
            $user_deposit->update([
                'deposit_status' => 1,
                'is_confirm' => 1,
                'confirm_time' => time(),
                'confirm_by' => auth('admin')->id(),
                'one_time_confirm_token' => null
            ]);

            $options = $user_deposit->options ?: [];
            $options['verify_email'] = request()->email;
            $user_deposit->update([
                'options' => $options
            ]);

            // if(auth('admin')->id()){
            //     Helper::create_admin_log(63, [
            //         'id' => $user_deposit->id,
            //         'deposit_status' => 1,
            //         'confirm_by' => auth('admin')->id(),
            //         'is_confirm' => 1,
            //         'confirm_time' => time()
            //     ]);
            // }

            Toastr::success("Xác nhận mua coin thành công !");
                return redirect(route('home.index'));
        }
        else {
            Toastr::error("Đã xác nhận mua coin hoặc không tồn tại !");
            return redirect(route('home.index'));
        }
    }

    /**
     * Get user transactions
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function transaction()
    {
        $user = Auth::guard('user')->user();
        $params['deposit_list'] = DB::table('user_deposit as ude')
            ->select('ude.deposit_time', 'ude.deposit_type', 'ude.deposit_code', 'pme.payment_name', 'ude.deposit_note',
                'ude.deposit_amount', 'utr.voucher_discount_percent', 'ude.deposit_status', DB::raw("ROW_NUMBER() over (order by ude.id)as num"))
            ->whereRaw("ude.user_id = $user->id ")
            ->join('payment_method as pme', 'ude.payment_method_id', '=', 'pme.id')
            ->join('user_transaction as utr', 'ude.user_transaction_id', '=', 'utr.id')
            ->paginate(20);

        return view('user.deposit.transaction', $params);
    }


    /**
     * get invoice
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function invoice()
    {
        $user = Auth::guard('user')->user();
        $params['deposit_list'] = DB::table('user_deposit')->select('deposit_code', 'deposit_time')
            ->where('deposit_status', '=', 2)
            ->where('user_id', '=', $user->id)
            ->get();
        $params['invoice_request'] = DB::table('bill_service as bis')
            ->select('bis.id', 'bis.created_at', 'bis.bill_url', 'bis.confirm_status','bis.company_name', 'bis.company_representative', 'dep.deposit_code', 'dep.deposit_amount', 'dep.deposit_type')
            ->where('bis.user_id', $user->id)
            ->join('user_deposit as dep', 'bis.transaction_id', 'dep.user_transaction_id')
            ->paginate(20);

        $params['guide'] = DB::table('user_guide')
            ->where('is_deleted', 0)
            ->where('id', 2)->value('guide_content');

        return view('user.deposit.invoice', $params);
    }


    /**
     * post invoice request
     * @param InvoiceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_request_invoice(InvoiceRequest $request)
    {
        $user = Auth::guard('user')->user();
        $deposit = DB::table('user_deposit')->where('deposit_code', '=', $request->deposit_code)->first();
        if (!$deposit) {
            Toastr::error('Yêu cầu hóa đơn không thành công');
            return redirect()->back();
        }

        $invoice_data = [
            'user_id' => $user->id,
            'bill_type' => $request->invoice_type,
            'transaction_id' => $deposit->user_transaction_id,
            'bill_note' => $request->invoice_content,
            'company_name' => $request->company_name,
            'company_representative' => $request->fullname,
            'company_address' => $request->address,
            'tax_code' => $request->tax_number,
            'created_at' => time(),
        ];

        DB::table('bill_service')->insert($invoice_data);
        Toastr::success('Yêu cầu hóa đơn thành công');
        return redirect()->back();

    }


    /**
     * Tai hoa don
     * @param $id
     * @return mixed
     */
    public function download($id)
    {
        $user = Auth::guard('user')->user();
        $file_url = DB::table('bill_service')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->value('bill_url');
        $filepath = public_path($file_url);
        return Response::download($filepath);
    }
}
