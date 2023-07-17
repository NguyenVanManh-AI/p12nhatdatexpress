<?php

namespace App\Http\Controllers\User;

use App\CPU\ClassifiedService as CPUClassifiedService;
use App\CPU\ServiceFee;
use App\Enums\UserViolateAction;
use App\Enums\UserViolateStatus;
use App\Helpers\Helper;
use App\Helpers\SystemConfig;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Classified\AddClassifiedRequest;
use App\Http\Requests\User\Classified\EditClassifiedRequest;
use App\Models\AdminConfig;
use App\Models\Classified\Classified;
use App\Models\ClassifiedPackage;
use App\Models\Direction;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Str;
use File;
use App\Models\Project;
use App\Models\Province;
use App\Services\Classifieds\ClassifiedService;
use App\Services\GroupService;
use App\Services\ParamService;
use App\Services\UserService;

class ClassifiedController extends Controller
{
    private ClassifiedService $classifiedService; 
    private GroupService $groupService;
    private ParamService $paramService;
    private UserService $userService;

    public function __construct()
    {
        $this->classifiedService = new ClassifiedService;
        $this->groupService = new GroupService;
        $this->paramService = new ParamService;
        $this->userService = new UserService;
    }

    /**
     * danh sach tin dang
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::guard('user')->user();
        $itemsPerPage = (int) $request->items ?: 10;
        $page = (int) $request->page ?: 1;

        #chuyen muc dang tin
        $params['parent'] = DB::table('group')->select('id', 'group_name')
            ->whereIn('id', [2, 10, 18])
            ->get();

        $params['classifieds'] = Classified::query()
            ->join('group', 'classified.group_id', '=', 'group.id')
            ->select(
                'classified.id', 'classified.classified_name', 'classified.active_date', 'classified.expired_date',
                'classified.is_show', 'classified.confirmed_status', 'classified.num_view', 'classified.num_view_today',
                'group.group_name', 'group.group_url', 'classified.is_vip', 'classified.vip_begin', 'classified.vip_end',
                'classified.is_hightlight', 'classified.hightlight_begin', 'classified.hightlight_end', 'classified.expired_date',
                'classified.group_id', 'classified.classified_url'
            )
            ->where('user_id', $user->id)
            ->when($request->paradigm, function ($query, $paradigm) {
                $query->where('classified.group_id', $paradigm);
            })
            ->when($request->progress, function ($query, $progress) {
                $query->where('classified.classified_progress', $progress);
            })
            ->when($request->classified_name, function ($query, $name) {
                $query->where('classified.classified_name', 'LIKE', '%' . $name . '%');
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                $query->where('classified.created_at', '>=', strtotime($dateFrom));
            })
            ->when($request->date_to, function ($query, $dateTo) {
                $query->where('classified.created_at', '<=', strtotime($dateTo));
            })
            ->when($request->classified_type, function ($query, $type) {
                $now = time();

                switch ($type) {
                    case 1:
                        $query->where('classified.is_vip', 1)
                            ->where('classified.vip_begin', '<=', $now)
                            ->where('classified.vip_end', '>', $now)
                            ->where('classified.expired_date', '>', $now);
                        break;
                    case 2:
                        $query->where('classified.is_hightlight', 1)
                            ->where('classified.hightlight_begin', '<=', $now)
                            ->where('classified.hightlight_end', '>', $now)
                            ->where('classified.expired_date', '>', $now);
                        break;
                    case 3:
                        $query->where(function ($q) use ($now) {
                            $q->where(function ($q) {
                                return $q->whereNull('classified.is_hightlight')
                                    ->whereNull('classified.is_vip');
                            })
                            ->orWhere(function ($q) use ($now) {
                                return $q->where('classified.is_vip', 1)
                                    ->where('classified.vip_end', '<=', $now);
                            })
                            ->orWhere(function ($q) use ($now) {
                                return $q->where('classified.is_hightlight', 1)
                                    ->where('classified.hightlight_end', '<=', $now);
                            })
                            ->orWhere('classified.expired_date', '<=', $now);
                        });
                        break;
                    default:
                        break;
                }

                return $query;
            })
            ->latest('classified.created_at')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        $params['paradigm'] = collect($this->get_child_group($request->parent));
        $params['progress'] = DB::table('progress')->select('id', 'progress_name')->where('group_id', $request->paradigm)->get();
        $params['service'] = DB::table('service_fee')->whereIn('id', [4, 5, 6])->get();

        $params['warning_forbidden_word'] = $user->violates()
            ->where('status', UserViolateStatus::ACTIVE)
            ->where('action', UserViolateAction::CLASSIFIED_FORBIDDEN_WORD)
            ->latest()
            ->first();

        // $params['countTrash'] = $user->classifieds()
        //     ->onlyIsDeleted()
        //     ->count();

        return view('user.classified.index', $params);
    }

    //ajax get project
    public function ajax_get_project($project_id)
    {
        $project_active = Project::findOrFail($project_id);
        return response()->json($project_active, 200);
    }
    /**
     * form dang tin
     * @param $group
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function add_classified(Request $request, $groupUrl)
    {
        // check group return if not in [can-mua-can-thue, nha-dat-ban, nha-dat-cho-thue]
        $group = $this->groupService->getGroupFromUrl($groupUrl);
        $groupId = data_get($group, 'id');

        if (!$group || !in_array($groupId, [2, 10, 18]))
            return redirect(route('user.list-classified'));

        //project active
        if($request->project){
            $params['project_active'] = Project::findOrFail($request->project);
        }
        $user = Auth::guard('user')->user();

        $params['group'] = $group;

        #mo hinh
        $groupParentId = $groupId == 18 ? old('classified_type', 19) : $groupId;
        $params['paradigm'] = $this->groupService->getChildrenSelectFromGroupId($groupParentId);

        // select options
        $params['progress'] = $this->groupService->getProgressFromId(old('paradigm'));
        $params['furniture'] = $this->groupService->getFurnituresFromId(old('paradigm'));

        $params['classifiedParams'] = $this->paramService->getClassifiedParamsByTypes(['A', 'B', 'L', 'P', 'T']);

        #Huong
        $params['direction'] = Direction::select('id', 'direction_name', 'is_show')
            ->showed()
            ->get();
        #don vi gia
        // $params['unit_price'] = DB::table('unit')->select('id', 'unit_name')->whereIn('id', $groupId == 2 ? [1, 2, 3, 4] : [3, 4, 5, 6])->get(); 
        // $params['unit_price'] = DB::table('unit')->select('id', 'unit_name')->whereIn('id', data_get($unitPriceMap, $groupParentId, []))->get(); 
        $params['unit_price'] = $this->paramService->getGroupUnitPrice($groupParentId);
        #Tinh/thanh pho
        $params['province'] = Province::select('id', 'province_name', 'is_show')
            ->showed()    
            ->get();
        #quan huyen
        #xa phuong
        #dich vu dang tin
        $params['service_fee'] = DB::table('service_fee')->select('id', 'service_name', 'service_coin')->whereIn('id', [2, 3])->get();
        #So coins trong tai khoan
        $params['coin_amount'] = DB::table('user')->where('id', $user->id)->value('coint_amount');
        #kiem tra tin dang hop le
        $params['classified_post'] = CPUClassifiedService::check_classified_post($user);
        #tai khoan xac thuc
        $params['account_verify'] = account_verify();

        #Goi tin kha dung
        $package = $this->userService->getCurrentBalance($user);

        // should change if auto check classified
        $normalPendingCount = $this->userService->getClassifiedPackagePendingCount($user);
        $vipPendingCount = $this->userService->getClassifiedPackagePendingCount($user, 'vip');
        $highLightPendingCount = $this->userService->getClassifiedPackagePendingCount($user, 'highlight');

        $packageData = [
            // buy package no limit for normal
            'normal' => $package->package_id != 1
                                        ? 'không giới hạn'
                                        : max((int) $package->classified_normal_amount - $normalPendingCount, 0),
            'vip' => max((int) $package->vip_amount - $vipPendingCount, 0),
            'highlight' => max((int) $package->highlight_amount - $highLightPendingCount, 0)
        ];
        $params['package'] = $packageData;

        #Danh sach du an
        $params['project'] = Project::select('id', 'project_name')
            ->showed()
            ->get();
        #note
        $params['guide'] = AdminConfig::select('config_code', 'config_value')->whereIn('config_code', ['N006', 'N007'])->get();

        $params['classified'] = new Classified([
            'price_unit_id' => data_get($params, 'unit_price.0.id'),
        ]);

        return view('user.classified.add-classified', $params);
    }

    /**
     * post tao tin dang
     * @param AddClassifiedRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_add_classified(AddClassifiedRequest $request)
    {
        $user = Auth::guard('user')->user();

        #Kiểm tra các điều kiện ràng buộc đăng tin
        $block_message = user_blocked();
        if ($block_message) {
            Toastr::error($block_message);
            return redirect()->back()->withInput();

        }

        #kiem tra so tin dang trong ngay, trong thang
        if ($request->classified_package == 1) {
            $postValid = CPUClassifiedService::check_classified_post($user);
            if ($postValid) {
                // should check
                $basePackage = ClassifiedPackage::find(1);
                $maxPerMonth = $basePackage->classified_nomal_amount ?: SystemConfig::CLASSIFIED_PER_MONTH;

                Toastr::error("Chỉ được đăng tối đa " . SystemConfig::CLASSIFIED_PER_DAY . " tin/ngnày, tối đa " . $maxPerMonth . " tin/tháng.
                Vui lòng nâng cấp gói tin, hoặt mua dịch vụ để tiếp tục đăng tin.");

                return redirect()->back()->withInput();
            }
        }

        // check forbidden word change status
        $forbiddenWords = [];
        $checkFields = [
            'title', 'description', 'meta_title', 'meta_desc'
        ];

        foreach ($checkFields as $field) {
            $checkForbiddenWords = (array) Helper::checkBlockedKeyword(data_get($request, $field), 1);
            $forbiddenWords = array_unique(array_merge($forbiddenWords, $checkForbiddenWords));
        }

        // meta_title and meta_desc 
        $content = $request->description;
        $cutParagraph = substr($content, 0, strpos($content,  ' ', 300));
        if(strlen($cutParagraph) > 320) $cutParagraph = substr($cutParagraph, 0, 300);

        #Dữ liệu chung cho 3 loại tin đăng
        $classified_data = [
            'classified_code' => get_classified_code(),
            'user_id' => $user->id,
            'project_id' => $request->project,
            'group_id' => $request->paradigm,
            'classified_progress' => $request->progress,
            'classified_name' => $request->title,
            'classified_description' => $request->description,
            'classified_url' => Str::slug($request->title) . time(),
            'is_broker' => $request->is_broker,
            'classified_area' => $request->area,
            'area_unit_id' => 7,
            'classified_price' => $request->price ?? null,
            'price_unit_id' => $request->unit_price ?? null,
            'classified_direction' => $request->direction ?? null,
            'num_bed' => $request->bedroom ?? null,
            'num_toilet' => $request->toilet ?? null,
            'video' => $request->video_url,
            'meta_title' => $request->title,
            'meta_key' => $request->meta_key,
            'meta_desc' => $cutParagraph,
            'confirmed_status' => $forbiddenWords ? 3 : 1,
            'unapproved_at' => $forbiddenWords ? now() : null,
            'is_show' => 1,
            'is_deleted' => 0,
            'created_at' => time(),
            'renew_at' => now(),
        ];

        #Lưu thư viện hình ảnh tin đăng
        $imageArr = [];
        $thumbnailPath = null;

        if ($request->gallery) {
            foreach ($request->gallery as $key => $image) {
                $imagePath = base64ToFile($image, SystemConfig::userDirectory());
                array_push($imageArr, $imagePath);

                // save first image to thumbnail
                if ($key == 0) {
                    // save thumbnail
                    $thumbnailPath = base64ToFile($image, SystemConfig::userDirectory(), 1, 200, 200);
                }
            }
        }

        $classified_data['image_thumbnail'] = $thumbnailPath;

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

        #kiểm tra tài khoản đã xác thực( nạp trên 100k)
        $is_verify = account_verify();
        #Tin chinh chu
        $is_verify ? $classified_data['is_monopoly'] = $request->is_monopoly : false;

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
            $is_verify ? $classified_data['is_mezzanino'] = $request->mezzanino : 0;
            $is_verify ? $classified_data['is_internet'] = $request->internet : 0;
            $is_verify ? $classified_data['is_balcony'] = $request->balcony : 0;
            $is_verify ? $classified_data['is_freezer'] = $request->freezer : 0;
        }

        # Chuyên mục nhà đất cần mua/cần thuê
        if ($request->group == 'can-mua-can-thue') {
            $classified_data['is_mezzanino'] = $request->mezzanino;
            $classified_data['is_internet'] = $request->internet;
            $classified_data['is_balcony'] = $request->balcony;
            $classified_data['is_freezer'] = $request->freezer;
        }

        DB::beginTransaction();
        try {
            #insert dữ liệu vào bản tin đăng
            $classified_id = DB::table('classified')->insertGetId($classified_data);

            #insert dữ liệu vào classified_location
            $location_data = [
                'classified_id' => $classified_id,
                'province_id' => $request->province,
                'district_id' => $request->district,
                'address' => $request->address,
                'map_latitude' => $request->latitude,
                'map_longtitude' => $request->longtitude
            ];
            DB::table('classified_location')->insert($location_data);

            DB::commit();

            $message = [
                'success' => true,
                'content' => 'Đăng tin thành công!'
            ];

            #mua them dich vu tin noi bat, tin vip, goi tin
            $packageServiceData = [
                'service' => $request->classified_service,
                'package' => $request->classified_package,
                'num_day' => $request->num_day,
            ];
            $this->classifiedService->updatePackageService($classified_id, $packageServiceData);

            // create classified violate
            if ($forbiddenWords) {
                $classified = Classified::find($classified_id);
                $violateData = [
                    'user_id' => $user->id,
                    'target_id' => $classified->id,
                    'target_type' => Classified::class,
                    'action' => UserViolateAction::CLASSIFIED_FORBIDDEN_WORD,
                    'action_url' => route('user.list-classified'),
                    'options' => [
                        'forbidden_words' => $forbiddenWords
                    ],
                    // log data
                    'log_id' => 14, // Đăng tin có chứa từ cấm
                    'log_message' => null,
                ];
                $this->userService->createViolate($user, $violateData);
                $this->userService->checkBanUser($user, UserViolateAction::CLASSIFIED_FORBIDDEN_WORD);

                $message = [
                    'success' => false,
                    'content' => 'Nội dung có chứa các từ bị cấm: ' . implode(', ', $forbiddenWords)
                ];
            }

            $message['success'] ? Toastr::success($message['content']) : Toastr::error($message['content']);
            return redirect(route('user.list-classified'));
        } catch (\Exception $exception) {
            DB::rollBack();
            Toastr::error('Có lỗi xảy ra, vui lòng liên hệ admin!');
            return redirect()->back()->withInput();
        }
    }

    /*
     * chinh saua tin dang
     * @params $id: classified id
     */
    public function edit_classified($id)
    {
        $user = Auth::guard('user')->user();

        $classified = Classified::select('classified.*')
            ->join('classified_location', 'classified.id', '=', 'classified_location.classified_id')
            ->where('user_id', $user->id)
            ->find($id);

        if (!$classified || !$classified->canEdit() || !$classified->group) {
            Toastr::error('Tin đăng không tồn tại!');
            return redirect(route('user.list-classified'));
        }

        $group = $classified->group;
        $ancestor = $this->groupService->getAncestorGroupFromGroup($group);
        $ancestorId = data_get($ancestor, 'id');

        if (!in_array($ancestorId, [2, 10, 18])) {
            return redirect(route('user.list-classified'));
        }

        $params['classified'] = $classified;

        #convert image to base64
        $params['gallery'] = fileToBase64(json_decode($params['classified']->image_perspective));

        #chuyen muc dang tin
        $params['parent'] = $ancestor;
        $groupParentId = $group->parent_id;
        $params['group_parent_id'] = $groupParentId;

        #Mo hinh
        $params['paradigm'] = $this->groupService->getChildrenSelectFromGroupId($groupParentId);

        // select options
        $paradigmId = old('paradigm', $classified->group_id);
        $params['progress'] = $this->groupService->getProgressFromId($paradigmId);
        $params['furniture'] = $this->groupService->getFurnituresFromId($paradigmId);

        $params['classifiedParams'] = $this->paramService->getClassifiedParamsByTypes(['A', 'B', 'L', 'P', 'T']);

        #vi tri
        $params['location'] = $classified->location;
        #Huong
        $params['direction'] = Direction::select('id', 'direction_name', 'is_show')
            ->showed()
            ->get();
        #Don vi tien
        $params['unit_price'] = DB::table('unit')->select('id', 'unit_name')
            ->whereIn('id', $groupParentId == 2 ? [1, 2, 3, 4] : [3, 4, 5, 6])
            ->get();

        $params['unit_price'] = $this->paramService->getGroupUnitPrice($groupParentId);
        
        #Tinh/thannh pho
        $params['province'] = DB::table('province')->select('id', 'province_name')->get();
        $params['service_fee'] = DB::table('service_fee')->select('id', 'service_name', 'service_coin')->whereIn('id', [1, 2, 3])->get();
        #Goi tin kha dung
        // use same as add classified if use auto check

        #So coin hien co
        $params['coin_amount'] = DB::table('user')->where('id', $user->id)->value('coint_amount');
        #Du an
        $params['project'] = Project::select('id', 'project_name')
            ->showed()
            ->get();

        #Xác thực tài khoản để đăng tin chính chủ
        $params['account_verify'] = account_verify();
        #Huong dan
        $params['guide'] = AdminConfig::select('config_code', 'config_value')->whereIn('config_code', ['N006', 'N007'])->get();

        return view('user.classified.edit-classified', $params);
    }

    /**
     * post chinh sua tin dang
     * @param EditClassifiedRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post_edit_classified(EditClassifiedRequest $request)
    {
        $user = Auth::guard('user')->user();
        $classified = Classified::select('classified.*')
            ->join('classified_location', 'classified.id', '=', 'classified_location.classified_id')
            ->where('user_id', $user->id)
            ->find($request->id);

        //Kiem tra tin tin dang ton tai
        if (!$classified || !$classified->canEdit()) {
            Toastr::error('Tin đăng không tồn tại!');
            return redirect()->back();
        }

        // edit nội dung chứa từ cấm change status thành chờ duyệt
        $confirmedStatus = $classified->confirmed_status == 3 ? 0 : $classified->confirmed_status;

        // meta_title and meta_desc 
        $content = $request->description;
        $cutParagraph = substr($content, 0, strpos($content,  ' ', 300));
        if(strlen($cutParagraph) > 320) $cutParagraph = substr($cutParagraph, 0, 300);
                
        #Dữ liệu chung cho 3 loại tin đăng
        $classified_data = [
            'project_id' => $request->project,
            'group_id' => $request->paradigm,
            'classified_progress' => $request->progress,
            'classified_name' => $request->title,
            'classified_description' => $request->description,
            'is_broker' => $request->is_broker,
            'classified_area' => $request->area,
            'classified_price' => $request->price ?? null,
            'price_unit_id' => $request->unit_price ?? null,
            'classified_direction' => $request->direction ?? null,
            'num_bed' => $request->bedroom ?? null,
            'num_toilet' => $request->toilet ?? null,
            'video' => $request->video_url,
            'meta_title' => $request->title,
            'meta_key' => $request->meta_key,
            'meta_desc' => $cutParagraph,
            'updated_at' => time(),
            'confirmed_status' => $confirmedStatus,
            'unapproved_at' => null
        ];

        #Lưu thư viện hình ảnh tin đăng
        if ($classified->image_progress) {
            $files = json_decode($classified->image_progress);
            File::delete($files);

        }

        $imageArr = [];
        if ($request->gallery) {
            foreach ($request->gallery as $image) {
                $imagePath = base64ToFile($image, SystemConfig::userDirectory());
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

        $is_verify = account_verify();
        #kiểm tra tài khoản đã xác thực( nạp trên 100k)
        $is_verify ? $classified_data['is_monopoly'] = $request->is_monopoly : false;

        #Dữ liệu riêng của từng chuyên mục đăng tin
        #Chuyện mục nhà đất bán
        if ($request->group == 2) {
            $classified_data['classified_juridical'] = $request->juridical;
            $classified_data['classified_furniture'] = $request->furniture ?? null;
        }

        #Chuyên mục nhà đất cho thuê
        if ($request->group == 10) {
            $classified_data['advance_stake'] = $request->deposit;
            $classified_data['num_people'] = $request->capacity;
            $is_verify ? $classified_data['is_mezzanino'] = $request->mezzanino : 0;
            $is_verify ? $classified_data['is_internet'] = $request->internet : 0;
            $is_verify ? $classified_data['is_balcony'] = $request->balcony : 0;
            $is_verify ? $classified_data['is_freezer'] = $request->freezer : 0;
        }

        if ($request->group == 18) {
            $classified_data['is_mezzanino'] = $request->mezzanino;
            $classified_data['is_internet'] = $request->internet;
            $classified_data['is_balcony'] = $request->balcony;
            $classified_data['is_freezer'] = $request->freezer;
        }

        DB::beginTransaction();
        try {
            #insert dữ liệu vào bản tin đăng
            DB::table('classified')
                ->where('id', $classified->id)
                ->where('user_id', $user->id)
                ->update($classified_data);

            #insert dữ liệu vào classified_location
            $location_data = [
                'province_id' => $request->province,
                'district_id' => $request->district,
                'ward_id' => $request->ward,
                'address' => $request->address,
                'map_latitude' => $request->latitude,
                'map_longtitude' => $request->longtitude
            ];

            DB::table('classified_location')
                ->where('classified_id', $classified->id)
                ->update($location_data);

            DB::commit();

            // should change | hide first
            #mua them dich vu tin noi bat, tin vip, goi tin
            Toastr::success('Chỉnh sửa thành công!');

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Có lỗi xảy ra, vui lòng liên hệ admin!');
        } finally {
            return redirect()->back();
        }
    }

    /*
     * xoa tin dang
     * @param: $id: classified id
     */
    public function delete_classified($classified_id)
    {
        $user = Auth::guard('user')->user();

        $classified = $user->classifieds()
            ->find($classified_id);

        $classified->delete();

        Toastr::success('Xóa tin đăng thành công');
        return redirect()->back();
    }

    /*
     * Lam moi tin
     * @params: id- classified id
     */
    public function renew(Classified $classified)
    {
        $user = Auth::guard('user')->user();
        $serviceStatus = null;

        if ($user->can('renew', $classified)) {
            $serviceStatus = ServiceFee::classified_fee(4);
            $message = $serviceStatus['message'];

            if ($serviceStatus['status']) {
                $updateData = [
                    // 'expired_date' => time() + SystemConfig::CLASSIFIED_EXISTS_TIME,
                    'renew_at' => now(),
                    'updated_at' => time()
                ];

                $classified->update($updateData);
            }
        } else {
            $message = 'Bạn không đủ quyền!';
        }

        data_get($serviceStatus, 'status') ? Toastr::success($message) : Toastr::error($message);

        return redirect()->back();
    }

    /*
     * nang cap tin vip
     * @params $id: classified id
     */
    public function upgrade(Classified $classified)
    {
        $user = Auth::guard('user')->user();
        $serviceStatus = null;

        if ($user->can('upgrade', $classified)) {
            $serviceStatus = ServiceFee::classified_fee(5);
            $message = $serviceStatus['message'];

            if ($serviceStatus['status']) {
                $updateData = [
                    'is_vip' => true,
                    'vip_begin' => time(),
                    'vip_end' => time() + data_get($serviceStatus, 'service.existence_time'),
                    'updated_at' => time()
                ];

                $classified->update($updateData);
            }
        } else {
            $message = 'Bạn không đủ quyền!';
        }

        data_get($serviceStatus, 'status') ? Toastr::success($message) : Toastr::error($message);

        return redirect()->back();
    }

    public function deleteMultiple(Request $request)
    {
        $user = Auth::guard('user')->user();

        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        $user->classifieds()
            ->find($ids)
            ->each(function($classified) {
                $classified->delete();
                // create_user_log()
            });

        Toastr::success('Xóa thành công');

        return redirect(route('user.list-classified'));
    }

    /**
     * lay group con
     * @param $parent_group_id
     * @return array|\Illuminate\Support\Collection
     */
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

}
