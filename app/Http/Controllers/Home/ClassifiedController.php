<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Classified\ProjectDataResource;
use App\Http\Resources\GroupSelectResource;
use App\Http\Resources\UnitPriceSelectResource;
use App\Models\Classified\Classified;
use App\Models\Classified\ClassifiedLocation;
use App\Models\Project;
use App\Services\Classifieds\ClassifiedService;
use App\Services\ParamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClassifiedController extends Controller
{
    private ClassifiedService $classifiedService;
    private ParamService $paramService;

    public function __construct()
    {
        $this->classifiedService = new ClassifiedService;
        $this->paramService = new ParamService;
    }

    /**
     * load more estate news in home page
     *
     * @return JsonResponse
     */
    public function getMoreEstateNews(): JsonResponse
    {
        $estateNews = $this->classifiedService->getEstateNews();

        $html = '';
        if($estateNews && $estateNews->count()) {
            foreach ($estateNews as $item) {
                $html .= view('components.news.classified.item', [
                    'item' => $item,
                    'watched_classified' => getWatchedClassifieds(),
                ])->render();
            }
        } else {
            $html .= view('components.home.classified.add-classified-button')->render();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
                'onLastPage' => $estateNews->onLastPage()
            ]
        ]);
    }

    /**
     * get selected project data for add/edit classified form
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getProjectFormData(Request $request): JsonResponse
    {
        $project = Project::with('location')
            ->find($request->project_id);

        if (!$project)
            return response()->json([
                'success' => false,
            ]);

        if ($project->group) {
            $groupParentId = $request->group_parent_id;
            $paradigm = $project->group->dependencies()
                ->firstWhere('parent_id', $groupParentId);
            $project->dependency_paradigm_id = data_get($paradigm, 'id');

            // selected unit price id
            $project->unit_price_id = $groupParentId == 2 || $groupParentId == 19
                ? $project->price_unit_id
                : $project->project_unit_rent_id;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'project' => (new ProjectDataResource($project))->resolve([])
            ]
        ]);
    }

    /**
     * get selected project data for add/edit classified form
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getParentGroupFormData(Request $request): JsonResponse
    {
        $groupParadigms = $this->paramService->getGroupChildren($request->group_parent_id);
        $groupUnitPrices = $this->paramService->getGroupUnitPrice($request->group_parent_id);

        return response()->json([
            'success' => true,
            'data' => [
                'group_paradigms' => GroupSelectResource::collection($groupParadigms),
                'group_unit_prices' => UnitPriceSelectResource::collection($groupUnitPrices),
            ]
        ]);
    }

    public function getRelates()
    {
        $data = [
            'classified_id' => request()->classified_id,
            'project_id' => request()->project_id,
            'accept_near' => request()->accept_near ? true : false,
            'load_individual' => true,
        ];
        $classifieds = $this->classifiedService->getRelates($data);

        $html = '';
        if($classifieds && $classifieds->count()) {
            foreach ($classifieds as $item) {
                $html .= view('components.news.classified.item', [
                    'item' => $item,
                    'watched_classified' => getWatchedClassifieds(),
                ])->render();
            }
        } else {
            $html .= view('components.home.classified.add-classified-button')->render();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
                'onLastPage' => true
            ]
        ]); 
    }

    public function preview(Request $request)
    {
        $user = Auth::guard('user')->user();

        $classifiedData = [
            'classified_code' => get_classified_code(),
            'user_id' => data_get($user, 'id'),
            'project_id' => $request->project,
            'group_id' => $request->paradigm,
            'classified_progress' => $request->progress,
            'classified_name' => $request->title,
            'classified_description' => $request->description,
            'classified_url' => Str::slug($request->title) . time(),
            'is_broker' => $request->is_broker ? true : false,
            'classified_area' => $request->area,
            'area_unit_id' => 7,
            'classified_price' => $request->price ?? null,
            'price_unit_id' => $request->unit_price ?? null,
            'classified_direction' => $request->direction ?? null,
            'num_bed' => $request->bedroom ?? null,
            'num_toilet' => $request->toilet ?? null,
            'video' => $request->video_url,
            'meta_title' => $request->title,
            'is_monopoly' => $request->is_monopoly ? true : false,
            'classified_juridical' => $request->juridical,
            'classified_furniture' => $request->furniture,
            'advance_stake' => $request->deposit,
            'num_people' => $request->capacity,
            'is_mezzanino' => $request->mezzanino,
            'is_internet' => $request->internet,
            'is_balcony' => $request->balcony,
            'is_freezer' => $request->freezer,
            'num_view' => 100,
        ];

        $classified = new Classified($classifiedData);

        $locationData = [
            'province_id' => $request->province,
            'district_id' => $request->district,
            'ward_id' => $request->ward,
            'address' => $request->address,
            'map_latitude' => $request->latitude,
            'map_longtitude' => $request->longtitude
        ];

        $classified->location = new ClassifiedLocation($locationData);
        $classified->user = $user;

        $group = $classified->group ? $classified->group->getLastParentGroup() : null;

        $key_search = [
            [
                'type' => 0,
                'title' => data_get($classified->group, 'group_name') . " " . data_get($classified->location, 'district.district_name')
            ],
            [
                'type' => 1,
                'title' => data_get($classified->group, 'group_name') . " " . data_get($classified->location, 'province.province_name'),
            ],
            [
                'type' => 0,
                'title' => $this->convert_string(data_get($classified->group, 'group_name') . " " . data_get($classified->location, 'district.district_name'))
            ],
            [
                'type' => 1,
                'title' => $this->convert_string(data_get($classified->group, 'group_name') . " " . data_get($classified->location, 'province.province_name'))
            ],
        ];

        $key_search = array_filter($key_search, function ($key) {
            return trim(data_get($key, 'title')) != '' ? true : false;
        }, 0);

        $html = view('user.classified.partials._popup-preview-content', [
            'classified' => $classified,
            'group' => $group,
            'key_search' => $key_search,
        ])->render();

        return response()->json([
            'success' => true,
            'data' => [
                'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
            ]
        ]);
        // return view('Home.Classified.Detail', compact('item', 'group', 'comment', 'key_search'));
    }

    function convert_string($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }
}
