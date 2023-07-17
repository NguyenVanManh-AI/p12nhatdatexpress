<?php

namespace App\Http\Controllers\Classifieds;

use App\Http\Controllers\Controller;
use App\Services\Classifieds\SearchService;
use App\Services\GroupService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private SearchService $searchService;
    private GroupService $groupService;

    /**
     * Inject search service
     */
    public function __construct()
    {
        $this->groupService = new GroupService;
        $this->searchService = new SearchService;
    }

    public function getFormData(Request $request)
    {
        $searchData = $this->searchService->getFormData($request->category);

        return response()->json([
            'success' => true,
            'data' => [
                'searchData' => $searchData,
            ]
        ]);
    }

    public function getParadigmData(Request $request)
    {
        $group = $this->groupService->getGroupFromUrl($request->category, $request->paradigm);
        $paradigmData = $this->searchService->getParadigmData($group);

        return response()->json([
            'success' => true,
            'data' => [
                'paradigmData' => $paradigmData,
            ]
        ]);
    }
}
