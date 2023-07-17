<?php

namespace App\View\Components\Admin\User;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Statistics extends Component
{
    public $total_user;
    public $personal;
    public $personal_news;
    public $personal_old;
    public $business;
    public $business_news;
    public $business_old;
    public $cvtv_old;
    public $cvtv_news;
    public $cvtv;
    public $user_news;
    public $user_old;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        #tổng số người dùng
        $total_user = User::select([
            'id',
            'user_type_id',
            'created_at'
        ]);
        $this->total_user = $total_user->count();
        # tổng tài khoản cá nhân
        $this->personal = DB::table('user')->where('user_type_id',1)->count();
        #tổng tài khoản doanh nghiệp
        $this->business = DB::table('user')->where('user_type_id',3)->count();
        #tổng tài khoản cvtv;
        $this->cvtv = DB::table('user')->where('user_type_id',2)->count();
        # tổng số user tuần hiện tại
        $user = User::select([
            'id',
            'user_type_id',
            'created_at'
        ])->whereBetween('created_at',[strtotime(Carbon::now()->startOfWeek()),strtotime(Carbon::now()->endOfWeek())])->get();
        #tổng số user tuần trước
        $this->user_old = User::whereBetween('created_at',[strtotime(Carbon::now()->addDay(-7)->startOfWeek()),strtotime(Carbon::now()->addDay(-7)->endOfWeek())])->count();
        $this->user_news = $user->count();

        #cá nhân tuần hiện tại
        $this->personal_news = User::where('user_type_id',1)->whereBetween('created_at',[strtotime(Carbon::now()->startOfWeek()),strtotime(Carbon::now()->endOfWeek())])->count();
        #cá nhân tuần trước
        $this->personal_old = User::where('user_type_id',1)->whereBetween('created_at',[strtotime(Carbon::now()->addDay(-7)->startOfWeek()),strtotime(Carbon::now()->addDay(-7)->endOfWeek())])->count();


        #doanh nghiệp tuần hiện tại
        $this->business_news = User::where('user_type_id',3)->whereBetween('created_at',[strtotime(Carbon::now()->startOfWeek()),strtotime(Carbon::now()->endOfWeek())])->count();
        #cá nhân tuần trước
        $this->business_old = User::where('user_type_id',3)->whereBetween('created_at',[strtotime(Carbon::now()->addDay(-7)->startOfWeek()),strtotime(Carbon::now()->addDay(-7)->endOfWeek())])->count();

        #cvtv tuần hiện tại
        $this->cvtv_news = User::where('user_type_id',2)->whereBetween('created_at',[strtotime(Carbon::now()->startOfWeek()),strtotime(Carbon::now()->endOfWeek())])->count();
        #cvtv tuần trước
        $this->cvtv_old = User::where('user_type_id',2)->whereBetween('created_at',[strtotime(Carbon::now()->addDay(-7)->startOfWeek()),strtotime(Carbon::now()->addDay(-7)->endOfWeek())])->count();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.user.statistics');
    }
}
