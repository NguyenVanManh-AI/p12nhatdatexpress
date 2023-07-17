<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Brian2694\Toastr\Facades\Toastr;

class StaticPageController extends Controller
{
    public function detail_page(){

        $page = StaticPage::showed()->where('page_url',request()->path())->first();
        if($page == null ){
            Toastr::error('Không tồn tại trang tĩnh');
            return back();
        }
     return view('Home.StaticPage.index',compact('page'));
    }
}
