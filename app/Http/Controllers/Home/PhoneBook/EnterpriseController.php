<?php

namespace App\Http\Controllers\Home\PhoneBook;

use App\Http\Controllers\Controller;
use App\Services\EnterpriseService;
use Illuminate\Http\Request;

class EnterpriseController extends Controller
{
    private EnterpriseService $enterpriseService;

    public function __construct()
    {
        $this->enterpriseService = new EnterpriseService;
    }

    // list
    public function index(Request $request)
    {
        $request['user_type_id'] = 3; // company
        $companies = $this->enterpriseService->index($request->all());

        $num_collection = collect(['num_cur' => $companies->currentPage() * $companies->perpage()]);
        $provinces = get_cache_province();

        return view('Home.Phonebook.Enterprise.index', [
            'companies' => $companies,
            'num_collection' => $num_collection,
            'provinces' => $provinces,
        ]);
    }

    // tự động load gọi ajax trả về item
    public function item_list(Request $request)
    {
        $request['user_type_id'] = 3; // company
        $companies = $this->enterpriseService->index($request->all());

        $html = '';
        foreach ($companies as $item) {
            $html .= view('components.home.phone-book.enterprise-item', [
                'item' => $item,
            ])->render();
        }

        return response()->json([
            'num' => $companies->perpage(),
            'html' => $html
        ]);
    }
}
