<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    #Danh sách khuyến mại
    public function index()
    {
        $user = Auth::guard('user')->user();
        $params['vouchers'] = DB::table('user_voucher')
            ->where('user_id', $user->id)
            ->where('start_date', '<=', time())
            ->where('end_date', '>=', time())
            ->orderBy('id', 'desc')
            ->paginate(20);
        return View::make('user.promotion.index', $params);
    }

    #Tin khuyến mại
    public function promotion_news()
    {
        $user = Auth::guard('user')->user();
        $params['promotion_news'] = DB::table('promotion_news as n')
            ->select('n.*', 'p.promotion_code', 'p.date_from')
            ->where('n.is_deleted', '<>', 1)
            ->leftJoin('promotion as p', 'n.promotion_id', '=', 'p.id')
            ->where('p.date_from', '<=', time())
            ->where(function ($query) {
                $query->whereNull('p.date_to');
                $query->orWhere('date_to','>=', time());
            })
            ->where('p.is_private', 1)
            ->whereNotIn('p.promotion_code', function ($query) use ($user) {
                $query->select('uv.voucher_code')
                    ->from('user_voucher as uv')
                    ->where('uv.user_id', $user->id);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        return View::make('user.promotion.promotion-news', $params);
    }

    #nhận mã khuyến mại
    public function receipt_promotion($promotion_code)
    {
        $user = Auth::guard('user')->user();
        $promotion = DB::table('promotion')->where('promotion_code', $promotion_code)->first();
        if (!$promotion || $promotion->date_from > time() || $promotion->date_to < time() || $promotion->is_deleted == 1 || $promotion->is_private != 1 || $promotion->is_show != 1)
        {
            Toastr::error('Khuyến mãi không khả dụng');
            return redirect()->back();
        }
        $voucher = DB::table('user_voucher')
            ->where('user_id', $user->id)
            ->where('voucher_code', $promotion->promotion_code)->first();
        if ($voucher)
        {
            Toastr::error('Khuyến mãi không khả dụng');
            return redirect()->back();
        }

        $voucher_data = [
            'user_id' => $user->id,
            'voucher_code' => $promotion->promotion_code,
            'amount' => $promotion->num_use,
            'voucher_type' => $promotion->promotion_type,
            'voucher_percent' => $promotion->value,
            'receipt_date' => time(),
            'start_date' => $promotion->date_from,
            'end_date' => $promotion->date_to
        ];
        DB::table('user_voucher')->insert($voucher_data);
        Toastr::success('Nhận khuyến mãi thành công');
        return redirect()->back();
    }
}
