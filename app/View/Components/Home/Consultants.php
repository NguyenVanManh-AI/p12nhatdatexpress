<?php

namespace App\View\Components\Home;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class Consultants extends Component
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
        //truy vấn các cvtv có highline
        //sắp xếp theo rating
        //nếu rating bằng nhau thì sắp xếp công ty được nhiều đánh giá hơn

        $districtLocation = getDistrictLocation();

            $consultant = User::select('user.*')
            ->with('detail', 'location.province')
            ->withCount('ratingUsers')
            ->where('user.user_type_id', 2)
            ->active()
            ->groupBy('user.id')
            ->latest('user.is_highlight')
            ->latest('user.rating',)
            ->latest('rating_users_count')
            ->take(5)
            ->get();

        // ->where('user.is_deleted',0)
        // ->where('user.is_locked',0)
        // ->where('user.is_forbidden',0)
        // ->leftJoin('user_detail','user_detail.user_id','user.id')
        // ->leftJoin('user_location','user_location.user_id','user.id')
        // ->leftJoin('province','province.id','user_location.province_id')
        // ->leftJoin('district','district.id','user_location.district_id')
        // ->leftJoin('user_rating','user_rating.user_id','user.id')
        // ->where('user.user_type_id',2)
        // ->select('user.id','user_detail.image_url','user_detail.fullname','user.user_code','district.district_name','province.province_name','user.phone_number',DB::raw('count(user_rating.user_id) as count'),
        //     'user.is_highlight',
        //     DB::raw('CONCAT(
        //     SUBSTR(user.phone_number, 1, 3),
        //     " ",
        //     SUBSTR(user.phone_number, 4, 3)
        //     ) AS num_hide'),
        //     DB::raw('CONCAT(
        //     SUBSTR(user.phone_number, 1, 3),
        //     " ",
        //     SUBSTR(user.phone_number, 4, 3),
        //     " ",
        //     SUBSTR(user.phone_number, 7)
        //     ) AS num_formatted')
        // )
        // ->when($districtLocation, function ($query, $districtLocation) {
        //     return $query->where('user_location.district_id', $districtLocation);
        // })
        // ->groupBy('user.id','user_detail.image_url','user_detail.fullname','user.user_code','district.district_name','province.province_name','user.phone_number', 'is_highlight')
        // ->orderBy('user.is_highlight','desc')
        // ->orderBy('user.highlight_time','desc')
        // ->orderBy('user.rating','desc')
        // ->orderBy('count','desc')
        // ->skip(0)->take(5);

        // $consultant =$consultant->get();
        return view('components.home.consultants',['consultant'=>$consultant]);
    }
}
