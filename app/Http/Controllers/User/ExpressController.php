<?php

namespace App\Http\Controllers\User;

use App\Helpers\SystemConfig;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Express\AddExpressRequest;
use App\Models\AdminConfig;
use App\Models\Banner\Banner;
use App\Models\Banner\BannerGroup;
use App\Models\Group;
use App\Models\User\UserTransaction;
use App\Models\User\UserVoucher;
use App\Services\BannerService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpressController extends Controller
{
    private BannerService $bannerService;

    public function __construct()
    {
        $this->bannerService = new BannerService;
    }

    #Danh sách quảng cáo express
    public function index()
    {
        $user = Auth::guard('user')->user();

        $params['banners'] = DB::table('banner', 'b')
            ->select('b.*', 'bg.banner_type', 'bg.banner_group_name', 'bg.banner_name', 'g.group_name', 'ut.total_coin_amount')
            ->where('b.user_id', $user->id)
            ->join('banner_group as bg', 'b.banner_group_id', '=', 'bg.id')
            ->join('group as g', 'b.group_id', '=', 'g.id')
            ->join('user_transaction as ut', 'b.transaction_id', '=', 'ut.id')
            ->latest()
            ->paginate(20);

        return view('user.express.index', $params);
    }

    #tạo quảng cáo express
    public function add_express()
    {
        $params['group'] = Group::select('id', 'group_name')
            ->whereNull('parent_id')
            ->where('id', '<>', 1)->get();
        $params['banner'] = BannerGroup::select('id', 'banner_group','banner_name', 'banner_group_name', 'banner_position', 'banner_coin_price', 'banner_type', 'banner_width', 'banner_height')
            ->showed()
            ->where('banner_permission', 1)
            ->get();

        $params['guide'] = AdminConfig::query()
            ->whereIn('config_code', ['N002','N003'])
            ->get();

        // return view('user.express.add-express', $params);
        return view('user.express.create', $params);
    }

    public function post_express(AddExpressRequest $request)
    {
        DB::transaction(function ()use($request){
            $user = Auth::guard('user')->user()->fresh();
            $isChild = false;

            if ($request->banner_group == 'H') {
                // home page
                $group = Group::firstWhere('group_url', 'trang-chu');
                $groupId = data_get($group, 'id', 1);
            } else {
                // paradigm page
                $category = Group::find($request->category); // category required if banner group = C
                $paradigm = Group::find($request->paradigm);

                $groupId = data_get($paradigm, 'id', $category->id);
                $isChild = $paradigm ? true : false;
            }

            $banner_group = BannerGroup::where('banner_group', $request->banner_group)
                ->where('banner_permission', 1)
                ->where('banner_position', $request->banner_position)
                ->where('is_child', $isChild)
                ->firstWhere('banner_type', $request->banner_type);

            if (!$banner_group)
            {
                Toastr::error('Tạo chiến dịch không thành công');
                return redirect()->back()->withInput();
            }

            #Kiểm tra voucher
            $voucher = UserVoucher::select('id', 'voucher_percent')
                ->whereRaw("user_id = $user->id and start_date <= ".time()." and end_date >= ". time()." and amount_used < amount and voucher_type = 0")
                ->where('voucher_code', $request->deposit_voucher)->first('id');
            if ($request->voucher)
            {
                if (!$voucher)
                {
                    Toastr::error('Voucher không hợp lệ');
                    return redirect()->back()->withInput();
                }
            }

            // already validated
            #Kiểm tra Ngày hiển thị đã được thuê chưa
            // $sql = $request->paradigm?"group_id = $request->paradigm":true ." and ((date_from <= $date_from and date_to <= $date_to) or (date_from >= $date_from and date_to >= $date_from) or (date_from >= $date_to and date_to >= $date_to))";
            // $banner = DB::table('banner')
            //     ->whereRaw($sql)
            //     ->first();

            // $banner = Banner::where('group_id', $groupId)
            //     ->where('banner_group_id', $banner_group->id)
            //     ->where('date_to', '>=', $date_from)
            //     ->where('date_from', '<=', $date_to)
            //     ->firstWhere('is_deleted', 0);

            // if ($banner)
            // {
            //     Toastr::error('Ngày chiến dịch không khả dụng');
            //     return redirect()->back()->withInput();
            // }

            $date_from = strtotime($request->date_from);
            $date_to = strtotime($request->date_to) + 86399;

            #Kiểm tra số dư coin hợp lệ
            $total_banner_days = 1 + floor(($date_to - $date_from)/86400);
            // $total_coin = $banner_group->banner_price*$total_banner_days;
            $total_coin = $banner_group->banner_coin_price * $total_banner_days;
            $discount_coin = $voucher?$total_coin*$voucher->voucher_percent/100:0;

            if ($user->coint_amount - ($total_coin - $discount_coin) < 0)
            {
                Toastr::error('Không đủ express coins');
                return redirect()->back()->withInput();
            }
            #Giảm count sau khi trừ giá mua banner
            $user->coint_amount = $user->coint_amount - ($total_coin - $discount_coin);
            $user->save();

            #Xử lý banner
            UserVoucher::query()
                ->where('voucher_code', $request->voucher)
                ->update(['amount_used' => $voucher?$voucher->amount_used + 1:0]);

            #Tạo transaction
            $transaction_data = [
                'user_id' => $user->id,
                'transaction_type' => 'B',
                'banner_group_id' => $banner_group->id,
                'coin_amount' => $total_coin,
                'user_voucher_id' => $voucher?$voucher->id:null,
                'voucher_discount_percent' => $voucher?$voucher->voucher_percent:0,
                'voucher_discount_coin' => $discount_coin,
                'total_coin_amount' => ($total_coin - $discount_coin),
                'created_at' => time(),
                'created_by'=> $user->id,
            ];
            $transaction = UserTransaction::create($transaction_data);

            #Lưu ảnh banner
            // $image_path =  file_helper($request->banner_image, "uploads/users/$user->user_code");
            $image_path = base64ToFile(
                $request->banner_image,
                SystemConfig::userDirectory(),
                false,
                data_get($banner_group, 'banner_width', 0),
                data_get($banner_group, 'banner_height', 0)
            );

            #Tạo banner
            $banner_data = [
                'banner_group_id' => $banner_group->id,
                'group_id' => $groupId,
                'image_url' => $image_path,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'is_confirm' => 0,
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'created_at' => time(),
                'created_by'=> $user->id,
                'link' => $request->banner_link,
                'target_type' => 1 // default is open new tab
            ];
            $this->bannerService->create($banner_data);
            Toastr::success('Tạo quảng cáo express thành công');
        });

        return redirect(route('user.express'));
    }

    public function ajax_express_time(Request $request)
    {
        $existTime = Banner::showed()
            ->select(DB::raw("DATE_FORMAT(FROM_UNIXTIME('date_from'), '%Y-%m-%d') as from_date, DATE_FORMAT(FROM_UNIXTIME('date_from'), '%Y-%m-%d') as to_date"))
            ->whereIn('is_confirm', [1,0])
            ->where(function ($query) {
                $query->where('date_from', '>=', time());
                $query->orWhere('date_to', '>=', time());

            })
            ->where('banner_group_id', $request->position)
            ->where('group_id', $request->paradigm)
            ->where('date_from')
            ->get();

        return response()->json(['dateList' => $existTime, 'status' => 'success'], 200);

    }
}
