<?php

namespace App\Http\Controllers\Home;

use App\Helpers\SystemConfig;
use App\Http\Controllers\Controller;
use App\Http\Requests\Home\Classified\AddClassifiedRequest;
use App\Models\AdminConfig;
use App\Models\Classified\Classified;
use App\Models\Project;
use App\Models\Survey;
use App\Services\GroupService;
use App\Services\ParamService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;
use Str;

class GuestController extends Controller
{
    private GroupService $groupService;
    private ParamService $paramService;

    public function __construct()
    {
        $this->groupService = new GroupService;
        $this->paramService = new ParamService;
    }

    public function add_classified($groupUrl)
    {
        // check not allow created more than 1 classified for unique ip address
        $duplicateIpAddress = Classified::whereBetween('created_at', [now()->startOfDay()->timestamp, now()->endOfDay()->timestamp])
            ->firstWhere('ip', request()->ip());

        if ($duplicateIpAddress) {
            Toastr::error('Mỗi IP Chỉ được đăng 1 tin rao/ ngày nếu không đăng nhập, Vui lòng đăng nhập để đăng tin.');
            return redirect(route('home.index'));
        }

        // check group return if not in [can-mua-can-thue, nha-dat-ban, nha-dat-cho-thue]
        $group = $this->groupService->getGroupFromUrl($groupUrl);
        $groupId = data_get($group, 'id');

        if (!$group || !in_array($groupId, [2, 10, 18]))
            return redirect(route('home.index'));

        $params['group'] = $group;

        #mo hinh
        $childGroupId = $groupId == 18 ? old('classified_type') : $groupId;
        $params['paradigm'] = $this->groupService->getChildrenSelectFromGroupId($childGroupId);

        // select options
        $params['progress'] = $this->groupService->getProgressFromId(old('paradigm'));
        $params['furniture'] = $this->groupService->getFurnituresFromId(old('paradigm'));

        $params['classifiedParams'] = $this->paramService->getClassifiedParamsByTypes(['A', 'B', 'L', 'P', 'T']);

        #Huong
        $params['direction'] = DB::table('direction')->select('id', 'direction_name')->get();
        #don vi gia
        // $params['unit_price'] = DB::table('unit')->select('id', 'unit_name')->whereIn('id', $groupId == 2 ? [1, 2, 3, 4] : [3, 4, 5, 6])->get();
        $params['unit_price'] = DB::table('unit')->select('id', 'unit_name')->whereIn('id', $groupId == 2 ? [1, 3, 4] : [3, 4, 5, 6])->get();
        #Tinh/thanh pho
        $params['province'] = DB::table('province')->select('id', 'province_name')->get();
        #quan huyen
        $params['district'] = DB::table('district')->select('id', 'district_name')->where('province_id', old('province'))->get();
        #xa phuong
        $params['ward'] = DB::table('ward')->select('id', 'ward_name')->where('district_id', old('district'))->get();

        #Danh sach du an
        $params['project'] = Project::select('id', 'project_name')
            ->showed()
            ->get();
        #note
        $params['guide'] = AdminConfig::select('config_code', 'config_value')->whereIn('config_code', ['N006', 'N007'])->get();

        $params['classified'] = new Classified([
            'price_unit_id' => data_get($params, 'unit_price.0.id'),
        ]);

        return view('Home.guest.classified', $params);
    }

    public function post_add_classified(AddClassifiedRequest $request)
    {
        // check not allow created more than 1 classified for unique ip address // should update on validate with message
        $duplicateIpAddress = Classified::whereBetween('created_at', [now()->startOfDay()->timestamp, now()->endOfDay()->timestamp])
            ->firstWhere('ip', request()->ip());

        if ($duplicateIpAddress) {
            Toastr::error('Mỗi IP Chỉ được đăng 1 tin rao/ ngày nếu không đăng nhập, Vui lòng đăng nhập để đăng tin.');
            return redirect()->back()->withInput();
        }

        #Dữ liệu chung cho 3 loại tin đăng
        $classified_data = [
            'classified_code' => get_classified_code(),
            'user_id' => null,
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
            'meta_title' => $request->meta_title,
            'meta_key' => $request->meta_key,
            'meta_desc' => $request->meta_desc,
            'confirmed_status' => 1,
            'is_show' => 1,
            'created_at' => time(),
            'contact_name' => $request->contact_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'contact_address' => $request->contact_address,
            'ip' => $request->ip(),
            'renew_at' => now(),
        ];

        #Lưu thư viện hình ảnh tin đăng
        $imageArr = [];
        if ($request->gallery) {

            foreach ($request->gallery as $image) {
                $imagePath = base64ToFile($image, 'uploads/users/guest');
                array_push($imageArr, $imagePath);
            }
        }
        if ($request->gallery_project) {
            $imageArr = array_merge($imageArr, $request->gallery_project);
        }
        $classified_data['image_perspective'] = json_encode($imageArr);

        #Xử lý thời gian hiển thị tin
        if ($request->date_from && !$request->date_to) {
            $classified_data['active_date'] = strtotime($request->date_from);
            $classified_data['expired_date'] = $classified_data['active_date'] + SystemConfig::CLASSIFIED_EXISTS_TIME;

        } elseif ($request->date_from && $request->date_to) {
            $date_from = strtotime($request->date_from);
            $date_to = strtotime($request->date_to);
            $dayTime = $date_to - $date_from;

            if (($date_from < $date_to) && ($dayTime <= SystemConfig::CLASSIFIED_EXISTS_TIME)) {
                $classified_data['active_date'] = $date_from;
                $classified_data['expired_date'] = $date_to;

            } else {
                $classified_data['active_date'] = time();
                $classified_data['expired_date'] = time() + SystemConfig::CLASSIFIED_EXISTS_TIME;

            }
        } else {
            $classified_data['active_date'] = time();
            $classified_data['expired_date'] = time() + SystemConfig::CLASSIFIED_EXISTS_TIME;

        }

        #Dữ liệu riêng của từng chuyên mục đăng tin
        #Chuyện mục nhà đất bán
        if ($request->group == 'nha-dat-ban') {
            $classified_data['classified_juridical'] = $request->juridical;
            $classified_data['classified_furniture'] = $request->furniture ?? null;
        }

        #Chuyên mục nhà đất cho thuê
        if ($request->group == 'nha-dat-cho-thue') {
            $classified_data['advance_stake'] = $request->deposit;
            $classified_data['num_people'] = $request->capacity;
            // guest not have utilities
            // $classified_data['is_mezzanino'] = $request->mezzanino ? true : 0;
            // $classified_data['is_internet'] = $request->internet ? true : 0;
            // $classified_data['is_balcony'] = $request->balcony ? true : 0;
            // $classified_data['is_freezer'] = $request->freezer ? true : 0;
        }

        // guest not have utilities
        # Chuyên mục nhà đất cần mua/cần thuê
        // if ($request->group == 'can-mua-can-thue') {
        //     $classified_data['is_mezzanino'] = $request->mezzanino;
        //     $classified_data['is_internet'] = $request->internet ? true : 0;
        //     $classified_data['is_balcony'] = $request->balcony ? true : 0;
        //     $classified_data['is_freezer'] = $request->freezer ? true : 0;
        // }


        DB::beginTransaction();
        try {
            #insert dữ liệu vào bản tin đăng
            $classified_id = DB::table('classified')->insertGetId($classified_data);

            #insert dữ liệu vào classified_location
            $location_data = [
                'classified_id' => $classified_id,
                'province_id' => $request->province,
                'district_id' => $request->district,
                'ward_id' => $request->ward,
                'address' => $request->address,
                'map_latitude' => $request->latitude,
                'map_longtitude' => $request->longtitude
            ];
            DB::table('classified_location')->insert($location_data);

            DB::commit();
            Toastr::success('Đăng tin thành công!');

            #mua them dich vu tin noi bat, tin vip, goi tin

        } catch (\Exception $exception) {
            DB::rollBack();
            Toastr::error('Có lỗi xảy ra, vui lòng liên hệ admin!');

            return redirect()->back()->withInput();
        }

        return redirect(route('home.index'));
    }

    public function get_child_group($parent_group_id)
    {
        $children = [];
        if ($parent_group_id == null) {
            return $children;
        }
        $children = DB::table('group')->where('parent_id', $parent_group_id)->get();
        foreach ($children as $item) {
            $item->children = $this->get_child_group($item->id);
        }
        return $children;
    }


    /**
     * Lay group cha
     * @param $childId
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function get_ancestor_group($childId)
    {
        $group = DB::table('group')->where('id', $childId)->first();
        if ($group->parent_id) {
            $group = $this->get_ancestor_group($group->parent_id);
        }
        return $group;
    }

    /**
     * save guest website survey ratings
     *
     * @return Response
     */
    public function websiteSurvey(Request $request)
    {
        $webSurveys = session('web_surveys', []);
        $type = $request->type;
        $rating = $request->rating;
        $oldRating = $request->old_rating;

        $survey = Survey::where('type', $type)
            ->firstWhere('rating', $oldRating);

        if(array_key_exists($type, $webSurveys) && $survey) {
            $survey->update([
                'rating' => $rating
            ]);
        } else {
            Survey::create([
                'type' => $type,
                'rating' => $rating
            ]);
        }

        $ratingData = Survey::select(DB::raw('avg(rating) as avg_rating, count(*) as length, type'))
            ->groupBy('type')
            ->where('type', $type)
            ->first();

        $webSurveys[$type] = $rating;
        session(['web_surveys' => $webSurveys]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bạn vừa thực hiện khảo sát',
            'data' => [
                'rating_value' => $rating,
                'rating' => $ratingData
            ]
        ], 200);
    }
}
