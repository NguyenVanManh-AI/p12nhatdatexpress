<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\PromotionNew;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    #Danh sách khuyến mại
    public function index(Request $request)
    {
        $itemsPerPage = $request->items ?: 10;
        $page = $request->page ?: 1;

        $user = Auth::guard('user')->user();

        $vouchers = $user->vouchers()
            ->latest('receipt_date')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return view('user.promotion.index', [
            'vouchers' => $vouchers
        ]);

        // $params['vouchers'] = DB::table('user_voucher')
        //     ->where('user_id', $user->id)
        //     ->where('start_date', '<=', time())
        //     ->where('end_date', '>=', time())
        //     ->orderBy('id', 'desc')
        //     ->paginate(20);
        // return View::make('user.promotion.index', $params);
    }

    #Tin khuyến mại
    public function promotion_news()
    {
        $user = Auth::guard('user')->user();
        $params['promotion_news'] = PromotionNew::select('promotion_news.*', 'promotion.promotion_code', 'promotion.date_from')
            ->leftJoin('promotion', 'promotion_news.promotion_id', '=', 'promotion.id')
            ->leftJoin('user_vouchers', 'user_vouchers.promotion_id', '=', 'promotion.id')
            ->where(function ($query) use ($user) {
                return $query->whereNull('user_vouchers.id')
                    ->orWhere('user_vouchers.user_id', '!=', $user->id);
            })
            ->where('promotion.date_from', '<=', time())
            ->where(function ($query) {
                $query->whereNull('promotion.date_to');
                $query->orWhere('promotion.date_to','>=', time());
            })
            ->where('promotion.is_private', 1)
            // ->whereNotIn('promotion.promotion_code', function ($query) use ($user) {
            //     $query->from('user_voucher')
            //         ->select('user_voucher.voucher_code')
            //         ->where('user_voucher.user_id', $user->id);
            // })
            ->latest('promotion_news.id')
            ->paginate(20);

        return View::make('user.promotion.promotion-news', $params);
    }

    #nhận mã khuyến mại
    public function receipt_promotion($code)
    {
        $user = Auth::guard('user')->user();

        $promotion = Promotion::showed()
            ->where('is_private', 1)
            ->firstWhere('promotion_code', $code);

        $promotion = DB::table('promotion')->where('promotion_code', $code)->first();
        if (!$promotion || $promotion->date_from > time() || $promotion->date_to < time())
        {
            Toastr::error('Khuyến mãi không khả dụng');
            return redirect()->back();
        }

        $user->vouchers()
            ->firstOrCreate([
                'promotion_id' => $promotion->id
            ], [
                'receipt_date' => now()
            ]);

        Toastr::success('Nhận khuyến mãi thành công');
        return back();
    }
}
