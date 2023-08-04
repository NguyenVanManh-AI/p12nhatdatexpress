<?php

namespace App\Http\Controllers\Param;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Group;
use App\Models\User\UserTransaction;
use App\Services\ParamService;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Project;
use App\Models\Province;
use App\Services\BannerService;
use App\Services\GroupService;

class ParamController extends Controller
{
    private BannerService $bannerService;
    private ParamService $paramService;
    private GroupService $groupService;

    /**
     * Inject service
     */
    public function __construct()
    {
        $this->groupService = new GroupService;
        $this->bannerService = new BannerService;
        $this->paramService = new ParamService;
    }

    public function get_district(Request $request)
    {
        $districts = DB::table('district')->select('id', 'district_name')->where('province_id', $request->province_id)->get();
        return response()->json(['districts' => $districts, 'status' => 'success'], 200);
    }

    public function get_ward(Request $request)
    {
        $wards = DB::table('ward')->select('id', 'ward_name')->where('district_id', $request->district_id)->get();
        return response()->json(['wards' => $wards, 'status' => 'success'], 200);
    }

    public function get_progress(Request $request)
    {
        $progress = DB::table('progress')->select('id', 'progress_name')->where('group_id', $request->group_id)->get();
        return response()->json(['progress' => $progress, 'status' => 'success'], 200);
    }

    public function get_progress_by_url(Request $request)
    {
        $group = DB::table('group')->where('group_url', $request->group_id)->first();
        $progress = DB::table('progress')->select('id', 'progress_name')->where('group_id', $group->id ?? -1)->get();
        return response()->json(['progress' => $progress, 'status' => 'success'], 200);
    }

    public function get_furniture(Request $request)
    {
        $furniture = DB::table('furniture')->select('id', 'furniture_name')->where('group_id', $request->group_id)->get();
        return response()->json(['furniture' => $furniture, 'status' => 'success'], 200);
    }

    public function get_furniture_by_url(Request $request)
    {
        $group = DB::table('group')->where('group_url', $request->group_id)->first();
        $furniture = DB::table('furniture')->select('id', 'furniture_name')->where('group_id', $group->id ?? -1)->get();

        return response()->json(['furniture' => $furniture, 'status' => 'success'], 200);
    }

    public function get_province_by_name(Request $request)
    {
        $provinceUrl = Helper::convertKebabCase($request->province_name);
        $province = Province::select('id', 'province_name', 'province_url')
            ->showed()
            ->where('province_name', $request->province_name)
            ->orWhere('province_url', $provinceUrl)
            ->first();

        return response()->json(['province' => $province, 'status' => 'success'], 200);
    }

    public function get_district_by_name(Request $request)
    {
        if (is_numeric($request->district_name))
            $request['district_name'] = "Quận $request->district_name";

        $districtUrl = Helper::convertKebabCase($request->district_name);

        $district = District::select('id', 'district_name', 'province_id')
            ->showed()
            ->where('district_name', $request->district_name)
            ->orWhere('district_url', $districtUrl)
            ->when($request->province_id, function($query, $provinceId) {
                return $query->where('province_id', $provinceId);
            })
            ->first();

        return response()->json(['district' => $district, 'status' => 'success'], 200);
    }

    public function get_ward_by_name(Request $request)
    {
        $ward = DB::table('ward')->select('id', 'ward_name')
            ->where(['ward_name' => $request->ward_name, 'district_id' => $request->district_id])->first();
        return response()->json(['ward' => $ward, 'status' => 'success'], 200);
    }

    public function get_image_project(Request $request)
    {
        $project = DB::table('project')
            ->where('id', $request->project_id)
            ->first();
        return response()->json(['project_image' => $project->image_url, 'status' => 'success'], 200);
    }

    public function get_group_child(Request $request)
    {
        $data =$this->groupService->getChildren($request->group_id);
        $group_child = $this->groupService->prepareData($data);
        $result = "";
        $result = $result . "<option selected value=''>Mô hình</option>";

        foreach ($group_child as $item) {
            if (!isset($item->child)) {
                $result = $result . "<option value='$item->id'>" . $item->group_name . "</option>";
            } else {
                $result = $result . "<option value='$item->id'>---- " . $item->group_name . "</option>";
            }
        }
        return response()->json(['group_child' => $result, 'status' => 'success'], 200);
    }


    /**
     * Get children group with json response
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_children_group(Request $request)
    {
        $groups = [];

        $parent = Group::query()
            ->when($request->parent_id, function ($query, $parentId) {
                return $query->where('id', $parentId);
            })
            ->when($request->group_url, function ($query, $groupUrl) {
                return $query->where('group_url', $groupUrl);
            })
            ->first();

        if ($parent)
            $groups = $parent->children()
                ->select('id', 'group_url', 'group_name')
                ->get();

        // if ($request->parent_id)
        //     $result = DB::table('group')->select('id', 'group_url', 'group_name')->where('parent_id', $request->parent_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'group_child' => $groups,
                'classified_search_prices' => config('constants.classified.search.price', []),
            ]
        ], 200);
    }

    /**
     * Check valid date banner
     * @param Request $request
     * @return int
     */
    public function checkIsvalidDateBanner(Request $request): int
    {
        try {
            // Check date
            $date_array = explode('-', $request->string_date);
            $preBeCheck = collect([]);
            $preBeCheck->put('date_from', date('Y-m-d', strtotime(trim(str_replace('/', '-', $date_array[0])))));
            $preBeCheck->put('date_to', date('Y-m-d', strtotime(trim(str_replace('/', '-', $date_array[1])))));
            $beCheck = CarbonPeriod::create($preBeCheck->get('date_from'), $preBeCheck->get('date_to'));

            $requestDate = collect([]);
            // Iterate over the period
            foreach ($beCheck as $date) {
                $requestDate->push(['date' => $date->format('Y-m-d')]);
            }
        } catch (\Exception $exception) {
            return -1;
        }


        $dates = UserTransaction::where('transaction_type', 'B')->with('banner')->whereHas('banner', function ($query) {
            $query->select('date_from', 'banner.date_to')->where('date_to', '>=', time());
        })->get()->pluck(['banner']);

        $isValidDates = collect([]);
        foreach ($dates as $item) {
            $dates = CarbonPeriod::create(date('Y-m-d', $item->date_from), date('Y-m-d', $item->date_to));
            // Iterate over the period
            foreach ($dates as $date) {
                $isValidDates->push(['date' => $date->format('Y-m-d')]);
            }
        }
        $isValidDates = $isValidDates->unique();

        // Group by date
        $groupedByValue = $isValidDates->merge($requestDate)->groupBy('date');
        $dupes = $groupedByValue->filter(function (Collection $groups) {
            return $groups->count() > 1;
        });

        return $dupes->count();
    }

    public function get_deposit(Request $request)
    {
        $user = Auth::guard('user')->user();
        $deposit_list = DB::table('user_deposit')->select('deposit_code', 'deposit_time')
            ->where('deposit_status', '=', 1)
            ->where('deposit_type', $request->deposit_type)
            ->where('user_id', '=', $user->id)
            ->get();
        return response()->json(['deposit_list' => $deposit_list, 'status' => 'success'], 200);
    }

    public function get_child_group(Request $request)
    {
        $groupChildren = $this->paramService->getGroupChildren($request->parent_id);

        return response()->json([
            'status' => true,
            'child_group' => $groupChildren,
        ], 200);
    }

    public function get_banner_price(Request $request)
    {
        $banner = DB::table('banner_group')
            ->where('banner_group', $request->banner_group)
            ->where('banner_position', $request->banner_pos)
            ->where('banner_permission', 1)
            ->where('banner_type', $request->banner_type)
            ->first();
        return response()->json(['banner' => $banner, 'status' => 'success'], 200);
    }

    /**
     * Get banner data for express
     * @return Response
     */
    public function getBannerGroupPriceData(Request $request)
    {
        $bannerData = $this->bannerService->getGroupPriceData($request->all());

        return response()->json([
            'success' => true,
            'data' => [
                'bannerData' => $bannerData
            ]
        ], 200);
    }

    public function set_location(Request $request)
    {
        Session::put('province', $request->province_location);
        Session::put('district', $request->district_location);
        Session::put('latLng', [
            'lat' => $request->lat_location,
            'lng' => $request->lng_location,
        ]);
        if ($request->accept_location != 0) {
            Session::put('accept_location', 1);
        }
        return back();
    }

    public function setGeolocation(Request $request)
    {
        if ($request->province_id)
            Session::put('province', $request->province_id);

        if ($request->district_id) {
            Session::put('district', $request->district_id);
            $district = District::find($request->district_id);
            if ($district) {
                Session::put('province', $district->province_id);
            }
        }

        if ($request->lat && $request->lng)
            Session::put('latLng', [
                'lat' => $request->lat,
                'lng' => $request->lng,
            ]);

        return response()->json([
            'success' => true,
        ], 200);
    }

    public function remove_location()
    {
//        Session::forget('province');
//        Session::forget('district');
//        Session::forget('latLng');
        Session::forget('accept_location');
        return back();
    }

    public function get_project_images(Request $request)
    {
        $projectImages = DB::table('project')
            ->find($request->project_id);

        return response()->json(['images' => json_decode($projectImages->image_url ?? ''), 'status' => 'success'], 200);

    }

    public function get_project_active(Request $request)
    {
        $project_active = Project::with('location')->findOrFail($request->project_id);
        return response()->json(['project_active' => $project_active, 'status' => 'success'], 200);
    }

    public function putIntendedUrl(Request $request)
    {
        if ($request->url)
            Session::put('url.intended', $request->url);

        return response()->json([
            'success' => true,
        ]);
    }
}
