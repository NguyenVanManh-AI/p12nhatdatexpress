<?php

namespace App\Http\Controllers\Admin\Seo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Seo\UpdateProvinceRequest;
use App\Services\Admins\SeoService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    private SeoService $seoService;

    public function __construct()
    {
        $this->seoService = new SeoService;
    }

    public function index(Request $request)
    {
        $provinces = $this->seoService->getSeoProvinces($request->all());

        return view('Admin.Seo.provinces.index', [
            'provinces' => $provinces
        ]);
    }

    public function edit($id)
    {
        $province = $this->seoService->getById($id);

        return view('Admin.Seo.provinces.edit', [
            'province' => $province
        ]);
    }

    public function update(UpdateProvinceRequest $request, $id)
    {
        $province = $this->seoService->getById($id);

        $this->seoService->updateProvince($province, $request->all());

        Toastr::success('Cập nhật thành công');
        return back();
    }
}
