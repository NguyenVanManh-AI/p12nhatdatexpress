<?php

namespace App\Http\Controllers\Admin\File;

use App\Http\Controllers\Controller;

class FileController extends Controller
{
    /** Index show File manager
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(){
        return view('Admin.File.Index');
    }
}
