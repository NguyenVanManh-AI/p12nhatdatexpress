<?php

namespace App\Http\Controllers\Home\PhoneBook;

use App\Http\Controllers\Controller;
use App\Services\EnterpriseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultantsController extends Controller
{
    private EnterpriseService $enterpriseService;

    public function __construct()
    {
        $this->enterpriseService = new EnterpriseService;
    }

    //list
    public function index(Request $request)
    {
        $request['user_type_id'] = 2; // consultant type id
        $consultants = $this->enterpriseService->index($request->all());


        // $consultants = DB::table("user")
        //     ->where('user.is_deleted',0)
        //     ->where('user.is_locked',0)
        //     ->where('user.is_forbidden',0)
        // ->leftJoin('user_detail','user_detail.user_id','user.id')
        // ->leftJoin('user_location','user_location.user_id','user.id')
        // ->leftJoin('province','province.id','user_location.province_id')
        // ->leftJoin('district','district.id','user_location.district_id')
        // ->leftJoin('user_rating','user_rating.user_id','user.id')
        // ->where('user.user_type_id',2)
        // ->select('user.id','user_detail.fullname','user_detail.image_url','user_location.address','district.district_name','province.province_name','user.email','user.phone_number','user_detail.facebook','user_detail.twitter','user.rating','is_highlight', DB::raw('count(user_rating.user_id) as count'),
        //     DB::raw('CONCAT(
        //     SUBSTR(user.phone_number, 1, 3),
        //     " ",
        //     SUBSTR(user.phone_number, 4, 3),
        //     " "
        //     ) AS num_hide'),
        //     DB::raw('CONCAT(
        //     SUBSTR(user.phone_number, 1, 3),
        //     " ",
        //     SUBSTR(user.phone_number, 4, 3),
        //     " ",
        //     SUBSTR(user.phone_number, 7)
        //     ) AS num_formatted')
        // )
        // ->groupBy('user.id','user_detail.fullname','user_detail.image_url','user_location.address','district.district_name','province.province_name','user.email','user.phone_number','user_detail.facebook','user_detail.twitter','user.rating', 'is_highlight')
        // ->orderBy('user.is_highlight','desc')
        // ->orderBy('user.highlight_time','desc')
        // ->orderBy('user.rating','desc')
        // ->orderBy('count','desc');

        // // search
        // $request->province_id!=""?$consultants->where('user_location.province_id','=',$request->province_id):null;
        // $request->district_id!=""?$consultants->where('user_location.district_id','=',$request->district_id):null;
        // $request->rate!=""?$consultants->where('user.rating','=',$request->rate):null;
        // $request->keyword!=""?$consultants->where('user_detail.fullname','like','%'.$request->keyword.'%'):null;

        // if ($request->project != ''){
        //     $consultants->leftJoin('project','project.id','=','user_detail.project_id')
        //         ->where('project.project_name','like','%'.$request->project.'%');
        // }

        // $consultants = $consultants->paginate(20);

        $num_collection = collect(['num_cur' => $consultants->currentPage() * $consultants->perpage()]);
        $provinces = get_cache_province();

        return view('Home.Phonebook.Consultants.index', [
            'consultants' => $consultants,
            'num_collection' => $num_collection,
            'provinces' => $provinces,
        ]);
    }

    // tự động load gọi ajax trả về item
    public function item_list(Request $request)
    {
        $request['user_type_id'] = 2; // consultant type id
        $consultants = $this->enterpriseService->index($request->all());

        $html = '';
        foreach ($consultants as $item) {
            $html .= view('components.home.phone-book.consultant-item', [
                'item' => $item,
            ])->render();
        }

        return response()->json([
            'num' => $consultants->perpage(),
            'html' => $html
        ]);
    }
}
