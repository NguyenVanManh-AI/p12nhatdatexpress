<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Ref\AddRequest;
use App\Http\Requests\User\Ref\ShareCoinRequest;
use App\Http\Requests\User\Ref\ShareVipRequest;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class ReferenceController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService;
    }

    public function index(Request $request)
    {
        $user = Auth::guard('user')->user()->fresh();
        if ($user->user_type_id == 2) {
            $params['reference'] = DB::table('user_detail')->select('fullname')
                ->where('user_id', $user->user_link_id)->first();
            return View::make('user.reference.agent', $params);
        }
        if ($user->user_type_id == 3) {
            $current_time = time();
            $sql = "u.id,u.user_code, u.email, u.phone_number, u.coint_amount, ude.image_url ,ude.fullname,
                (select vip_amount
                from user_balance
                where user_id = u.id
                and package_from_date <= $current_time
                and  package_to_date >= $current_time
                order by id desc
                limit 1
             )as vip_amount";
            $refs = DB::table('user as u')
                ->selectRaw($sql)
                ->where('user_link_id', $user->id)
                ->where('is_link_confirm', 1)
                ->join('user_detail as ude', 'u.id', '=', 'ude.user_id')
                ->orderBy('id', 'asc');

            $request->email ? $refs->where('u.email', $request->email) : null;
            $request->fullname ? $refs->where('ude.fullname', $request->fullname) : null;

            $params['refs'] = $refs->paginate(20);
            $params['current_balance'] = $this->userService->getCurrentBalance($user);

            return View::make('user.reference.enterprise', $params);
        }

        return redirect()->back();
    }

    #Xóa liên kết
    public function remove_reference($id = null)
    {
        if ($id) {
            $user = Auth::guard('user')->user();
            DB::table('user')
                ->where('id', $id)
                ->where('user_link_id', $user->id)
                ->update(['user_link_id' => null, 'is_link_confirm' => null]);
        } else {
            $user = Auth::guard('user')->user();
            $user->user_link_id = null;
            $user->save();
        }

        Toastr::success('Xóa liên kết thành công');
        return redirect()->back();
    }

    #Thêm liên kết tài khoản
    public function add_reference(AddRequest $request)
    {
        $user = Auth::guard('user')->user();

        #Kiểm tra tài khoản doanh nghiệp mới được tạo liên kết
        if ($user->user_type_id != 3) {
            Toastr::error('Chỉ có tài khoản doanh nghiệp mới được tạo liên kết');
            return redirect()->back();
        }

        #Kiểm tra liên kết tài khoản
        $ref_user = DB::table('user')
            ->where('user_code', $request->user_code)
            ->orWhere('email', $request->email)
            ->first();
        if ($ref_user->user_link_id) {
            Toastr::error('Tài khoản đang liên kết với tài khoản khác');
            return redirect()->back();
        }
        if ($ref_user->user_type_id != 2) {
            Toastr::error('Liên kết thất bại, chỉ tạo được liên kết với tài khoản chuyên viên tư vấn');
            return redirect()->back();
        }

        #Tạo liên kết tài khoản
        $ref_data = [
            'user_link_id' => $user->id,
            'is_link_confirm' => 0
        ];

        DB::table('user')
            ->where(function ($query) use ($request) {
                $query->where('user_code', $request->user_code)
                    ->orWhere('email', $request->email);
            })
            ->where('user_type_id', '2')
            ->update($ref_data);

        Toastr::success('Liên kết thành công');
        return redirect()->back();
    }

    #Chấp nhận liên kết tài khoản
    public function accrept_reference()
    {
        $user = Auth::guard('user')->user();
        DB::table('user')->where('id', $user->id)
            ->where('is_link_confirm', 0)
            ->where('user_type_id', 2)
            ->update(['is_link_confirm' => 1]);

        Toastr::success('Xác nhận liên kết thành công');
        return redirect()->back();
    }

    #Chi sẽ tin vip
    public function share_vip_amount(ShareVipRequest $request)
    {
        if ($request->share_vip_amount < 1) {
            Toastr::error('Số tin chia sẽ không hợp lệ');
            return redirect()->back();
        }
        $user = Auth::guard('user')->user();
        $current_vip = $params['current_balance'] = DB::table('user_balance')
            ->where('user_id', $user->id)
            ->where('package_from_date', '<=', time())
            ->where('package_to_date', '>=', time())
            ->orderBy('id', 'desc')
            ->first();
        if (!$current_vip || $current_vip->vip_amount < $request->share_vip_amount) {
            Toastr::error('Không đủ tin vip');
            return redirect()->back();
        }

        $ref_user = $params['refs'] = DB::table('user as u')
            ->where('user_code', $request->user_ref_share_vip)
            ->where('user_link_id', $user->id)
            ->where('is_link_confirm', 1)
            ->first();
        if (!$ref_user) {
            Toastr::error('Tài khoản liên kết không hợp lệ');
            return redirect()->back();
        }

        DB::table('user_balance')
            ->where('user_id', $user->id)
            ->update(['vip_amount' => $current_vip->vip_amount - $request->share_vip_amount]);

        $ref_balance_data = [
            'user_id' => $ref_user->id,
            'parent_id' => $user->id,
            'vip_amount' => $request->share_vip_amount,
            'package_from_date' => $current_vip->package_from_date,
            'package_to_date' => $current_vip->package_to_date,
            'created_at' => time(),
            'created_by' => $user->id
        ];

        DB::table('user_balance')->insert($ref_balance_data);

        Toastr::success('Chia sẽ tin vip thành công');
        return redirect()->back();

    }

    public function share_coin_amount(ShareCoinRequest $request)
    {
        if ($request->share_coin_amount < 1) {
            Toastr::error('Số coin chia sẽ không hợp lệ');
            return redirect()->back();
        }
        $user = Auth::guard('user')->user()->fresh();

        if ($request->share_coin_amount > $user->coin_amount) {
            Toastr::error('Không đủ coin');
            return redirect()->back();
        }
        $ref_user = $params['refs'] = DB::table('user as u')
            ->where('user_code', $request->user_ref_share_vip)
            ->where('user_link_id', $user->id)
            ->where('is_link_confirm', 1)
            ->first();
        if (!$ref_user) {
            Toastr::error('Tài khoản liên kết không hợp lệ');
            return redirect()->back();
        }
        DB::table('user')
            ->where('id', $user->id)
            ->update(['coin_amount' => $user->coin_amount - $request->share_coin_amount]);

        DB::table('user')
            ->where('id', $ref_user->id)
            ->update(['coin_amount' => $ref_user->coin_amount + $request->share_coin_amount]);

        $transaction_data = [
            'user_id' => $user->id,
            'transaction_type' => 'S',
            'coin_amount' => $request->share_coin_amount,
            'total_coin_amount' => $request->share_coin_amount,
            'created_at' => time(),
            'created_by' => $user->id
        ];
        DB::table('user_transaction')->insert($transaction_data);

        Toastr::success('Chia sẽ coin thành công');
        return redirect()->back();
    }
}
