<?php

namespace App\View\Components\Home;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Company extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        //truy vấn các công ty có highline
        //sắp xếp theo rating
        //nếu rating bằng nhau thì sắp xếp công ty được nhiều đánh giá hơn

        // $company = User::select('user.*', DB::raw('count(user_rating.user_id) as count'))
        $companyUsers = User::select('user.*')
            ->with('detail', 'location.province')
            ->withCount('ratingUsers')
            ->where('user.user_type_id', 3)
            ->active()
            ->groupBy('user.id')
            ->latest('user.is_highlight')
            ->latest('user.rating',)
            ->latest('rating_users_count')
            ->take(5)
            ->get();

        return view('components.home.company',[
            'companyUsers' => $companyUsers
        ]);
    }
}
