<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserGuide;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuideController extends Controller
{

    /**
     * danh sach huong dan
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $sqlQuery = UserGuide::showed()
            ->select('id', 'guide_title', 'guide_url', 'image_url')
            ->where('guide_type', 'N');
        
        if($request->title){
            $sqlQuery = $sqlQuery->where('guide_title', 'like', "%$request->title%");
        }

        $params['guides'] = $sqlQuery->paginate(20);
        return view('user.guide.index', $params);
    }


    /**
     * chi tiet huong dan
     * @param $guideUrl
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function detail($guideUrl)
    {
        $params['guide'] = UserGuide::query()
            ->where('guide_url', $guideUrl)
            ->where('guide_type', 'N')
            ->first();

        if ($params['guide']) {
            return view('user.guide.detail', $params);

        }

        Toastr::error('Hướng dẫn không tồn tại!');
        return  redirect()->back();
    }
}
