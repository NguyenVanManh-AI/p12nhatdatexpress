<?php

namespace App\Http\Controllers\Home\Classified;

use App\Http\Controllers\Controller;
use App\Http\Requests\Classified\RatingClassifiedRequest;
use App\Http\Requests\Classified\ReportCommentRequest;
use App\Http\Requests\Home\CustomerClassifiedRequest;
use App\Http\Requests\Home\Classified\ClassifiedReportRequest;
use App\Http\Requests\Home\Classified\SendAdvisoryRequest;
use App\Jobs\SendAdvisoryJob;
use App\Models\Classified\Classified;
use App\Models\Classified\ClassifiedComment;
use App\Models\Group;
use App\Models\User;
use App\Services\Classifieds\ClassifiedService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Models\User\Customer;
use App\Services\AdvisoryService;
use App\Services\GroupService;
use App\Services\ProjectService;
use App\Services\ProvinceService;
use App\Services\CustomerService;

class ClassifiedController extends Controller
{
    private AdvisoryService $advisoryService;
    private ClassifiedService $classifiedService;
    private CustomerService $customerService;
    private GroupService $groupService;
    private ProjectService $projectService;
    private ProvinceService $provinceService;

    public function __construct()
    {
        $this->advisoryService = new AdvisoryService;
        $this->classifiedService = new ClassifiedService;
        $this->customerService = new CustomerService;
        $this->groupService = new GroupService;
        $this->projectService = new ProjectService;
        $this->provinceService = new ProvinceService;
    }

    public function classified_list(Request $request, $group_url, $group_child = null)
    {
        $group = $this->groupService->getGroupFromUrl($group_url, $group_child);

        if (!$group) return abort(404);

        // get list classified
        $request['group_ids'] = $group->getChildrenGroupIds();
        $request['load_individual'] = true;
        $classifieds = $this->classifiedService->getListFromQuery($request->all());

        return view('Home.Classified.List-Classified', [
            'group' => $group,
            'classifieds' => $classifieds,
        ]);
    }

    /**
     * get search classified, nearly, expert.. for paradigm
     * @param string $group_url
     * @param string $group_child
     *
     * @return response
     */
    public function searchClassified($group_url, $group_child = null, Request $request)
    {
        $group = $this->groupService->getGroupFromUrl($group_url, $group_child);

        if (!$group) {
            return response()->json([
                'success' => false,
            ]);
        }

        // get list classified
        $request['group_ids'] = $group->getChildrenGroupIds();
        $request['load_individual'] = true;
        $classifieds = $this->classifiedService->getListFromQuery($request->all());

        $html = view('components.news.classified.search-results', [
            'group' => $group,
            'classifieds' => $classifieds
        ])->render();

        return response()->json([
            'success' => true,
            'data' => [
                'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
                'banner_image' => asset($group->image_banner ?? '/frontend/images/banner-duan.jpg')
            ]
        ]);
    }

     /**
     * get search category keywords | search title
     * @param string $group_url
     * @param string $group_child = null
     *
     * @return response
     */
    public function categorySearchKeyword($group_url, $group_child = null, Request $request)
    {
        $group = $this->groupService->getGroupFromUrl($request->category, $group_child);

        if (!$group) {
            // category group checkbox
            $group = $this->groupService->getGroupFromUrl($request->category);
        }

        if (!$group)
            return response()->json([
                'success' => false,
            ]);

        // get list classified
        $request['group_ids'] = $group->getChildrenGroupIds();
        $request['get_all'] = true;
        $request['search_title'] = $request->search;
        unset($request['search']);

        $type = 'classified';
        if ($group->getLastParentGroup() == 'du-an') {
            $type = 'project';
            $results = $this->projectService->getListFromQuery($request->all());
        } else  {
            $results = $this->classifiedService->getListFromQuery($request->all());
        }

        $html = view('home.search-dropdown-results', [
            'results' => $results,
            'type' => $type
        ])->render();

        return response()->json([
            'success' => true,
            'data' => [
                'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
            ]
        ]);
    }

    /**
     * get more items
     * @param string $group_url
     * @param string $group_child
     *
     * @return Response
     */
    public function getMoreItems($group_url, $group_child = null, Request $request)
    {
        $group = $this->groupService->getGroupFromUrl($group_url, $group_child);
        $province = null;

        // more item /vi-tri/nha-dat-{province_name}
        if (!$group && $group_url == 'vi-tri' && $group_child) {
            $provinceUrl = preg_replace('/nha-dat-/', '', $group_child);
            $province = $this->provinceService->getProvinceFromUrl($provinceUrl);
        }

        if (!$group && !$province) {
            return response()->json([
                'success' => false,
            ]);
        }

        if ($group) {
            $groupIds = $group->getChildrenGroupIds();
        } else {
            // province
            $buyGroup = $this->groupService->getGroupFromUrl('nha-dat-ban');
            $sellGroup = $this->groupService->getGroupFromUrl('nha-dat-cho-thue');
            $groupIds = $buyGroup->getChildrenGroupIds();
            $groupIds = array_unique(array_merge($groupIds, $sellGroup->getChildrenGroupIds()));
            $request['province_id'] = $province->id;
        }

        $request['group_ids'] = $groupIds;
        $classifieds = $this->classifiedService->getListFromQuery($request->all());

        $html = '';
        if($classifieds && $classifieds->count()) {
            foreach ($classifieds as $item) {
                $html .= view('components.news.classified.item', [
                    'item' => $item,
                    'watched_classified' => getWatchedClassifieds(),
                ])->render();
            }
        } elseif ($request->page == 1) {
            $html .= view('components.home.classified.add-classified-button')->render();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
                'onLastPage' => $classifieds->onLastPage()
            ]
        ]);
    }

    // should remove bcz old same classified_list diff $group->group_name | $province->province_name
    public function list_location_classified($province_url, $group_url = null, Request $request)
    {
        $province = DB::table('province')->where('province_url', $province_url)->first();
        if (!$province) return  abort(404);

        $group = $this->groupService->getGroupFromUrl($group_url);

        if (!$group) return abort(404);

        // get list classified
        $request['group_ids'] = $group->getChildrenGroupIds();
        $request['province_id'] = $province->id;
        $classifieds = $this->classifiedService->getListFromQuery($request->all());

        return view('Home.Classified.List-Classified', [
            'group' => $group,
            'classifieds' => $classifieds,
        ]);
    }

    public function viewMap($id)
    {
        $classified = Classified::query()
            ->select(
                'classified.*',
                // 'classified.id',
                // 'classified.contact_name',
                // 'classified.contact_phone',
                // 'classified.contact_email',
                // 'classified.contact_address',
                // 'classified_url',
                'user_id',
                'group_parent.id as group_parent_id',
                'group_parent_parent.id as group_parent_parent_id',
                'group_parent.group_url as group_parent_url',
                'group_parent_parent.group_url as group_parent_parent_url',
                'user_type.type_name',
            )
            ->with(
                'user.detail',
                'user.location',
                'location',
                'user_detail:id,image_url,fullname,website,facebook,zalo,twitter,youtube,user_id',
            )
            ->leftJoin('group', 'classified.group_id', 'group.id')
            ->leftJoin('group as group_parent', 'group.parent_id', 'group_parent.id')
            ->leftJoin('group as group_parent_parent', 'group_parent.parent_id', 'group_parent_parent.id')
            ->leftJoin('user', 'classified.group_id', 'group.id')
            ->leftJoin('user_type', 'classified.group_id', 'group.id')
            ->where('classified.id', $id)
            // ->where(function ($classified) {
            //     // should check and change
            //     $classified->where('group_parent.id', '=', 2)
            //         ->orWhere('group_parent.id', '=', 10)
            //         ->orWhere('group_parent_parent.id', '=', 2)
            //         ->orWhere('group_parent_parent.id', '=', 10)
            //         ->orWhere('group_parent.id', '=', 19)
            //         ->orWhere('group_parent.id', '=', 20)
            //         ->orWhere('group_parent_parent.id', '=', 19)
            //         ->orWhere('group_parent_parent.id', '=', 20);
            // })
            ->showed()
            ->first();

        if (!$classified)
            return response()->json([
                'success' => false,
            ], 403);

        $html = view('home.classifieds.partials._view-map', [
            'classified' => $classified,
        ])->render();

        return response()->json([
            'success' => true,
            'data' => [
                'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
                'location' => $classified->location,
            ]
        ], 200);
    }

    /**
     * Get detail classified
     * @param $group_url
     * @param $classified_url
     * @return \Illuminate\Contracts\Foundation\Application|
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function detail($group_url, $classified_url)
    {
        $group = Group::leftJoin('group as group_parent', 'group.parent_id', 'group_parent.id')
            ->leftJoin('group as group_parent_parent', 'group_parent.parent_id', 'group_parent_parent.id')
            ->select(
                'group.*',
                'group_parent.id as group_parent_id',
                'group_parent_parent.id as group_parent_parent_id',
                'group_parent.group_url as group_parent_url',
                'group_parent_parent.group_url as group_parent_parent_url'
            )
            ->where('group.group_url', $group_url)->first();

        $item = Classified::query()
            ->join('group', 'classified.group_id', '=', 'group.id')
            ->leftJoin('group as group_parent', 'group.parent_id', '=', 'group_parent.id')
            ->leftJoin('group as group_parent_parent', 'group_parent.parent_id', '=', 'group_parent_parent.id')
            ->with(
                'user.detail',
                'user.location',
                'user_detail:id,image_url,fullname,website,facebook,zalo,twitter,youtube,user_id',
                'user.user_location.province:id,province_name,province_type',
                'user.user_location.district:id,district_name,district_type',
                // 'user.user_location.ward:id,ward_name,ward_type',
            )
            ->select(
                'classified.*',
                'group_parent.id as group_parent_id',
                'group_parent.group_url as group_parent_url',
                'group_parent_parent.id as group_parent_parent_id',
                'group_parent_parent.group_url as group_parent_parent_url',
                'group.group_name',
                'group.group_url'
            )
            ->where("classified_url", $classified_url)
            ->showed()
            ->first();

        if ($item == null) {
            Toastr::error('Không tồn tại tin đăng');
            return back();
        }
        $item->num_view += 1;
        $item->num_view_today += 1;
        $item->save();
        $array_item = getWatchedClassifieds();

        if (in_array($item->id, $array_item)) {
            $array_item =  array_diff($array_item, array($item->id));
            array_push($array_item, $item->id);
        } else {
            array_push($array_item, $item->id);
        }
        Cookie::queue('watched_classified', json_encode($array_item), 60 * 24 * 30);
        $comment = ClassifiedComment::with('classified')
            ->leftJoin('classified', 'classified.id', '=', 'classified_comment.classified_id')
            ->leftJoin('classified_rating', function ($leftJoin) {
                $leftJoin->on('classified_comment.user_id', '=', 'classified_rating.user_id')
                    ->on('classified_rating.classified_id', '=', 'classified.id');
            })
            //                'classified_rating.classified_id','=','classified_comment.user_id')
            //            ->groupBy('classified_comment.id')
            //            ->groupBy('classified_comment')
            ->where([
                //            'classified_rating.classified_id'=>$item->id,
                'classified_comment.parent_id' => null,
                'classified_comment.classified_id' => $item->id,
            ])
            ->showed()
            ->orderBy('classified_comment.created_at', 'desc')
            ->select(
                'classified_comment.*',
                'classified_rating.star'
            )
            ->withCount('children', 'likes')
            ->paginate(5);

        $key_search = [
            [
                'type' => 0,
                'title' => $item->group_name . " " . $item->location->district->district_name
            ],
            [
                'type' => 1,
                'title' => $item->group_name . " " . $item->location->province->province_name,
            ],
            [
                'type' => 0,
                'title' => $this->convert_string($item->group_name . " " . $item->location->district->district_name)
            ],
            [
                'type' => 1,
                'title' => $this->convert_string($item->group_name . " " . $item->location->province->province_name)
            ],
        ];

        return view('Home.Classified.Detail', compact('item', 'group', 'comment', 'key_search'));
    }

    // Đánh giá tin đăng
    public function rating($classified_id, RatingClassifiedRequest $request)
    {
        $classified = Classified::showed()
            ->find($classified_id);

        if (!$classified) {
            return response()->json([
                'success' => false,
                'message' => 'Tin đăng không tồn tại!',
            ], 403);
        }

        $user = Auth::guard('user')->user();
        $request['user_id'] = data_get($user, 'id');
        $request['ip'] = $request->ip();
        $this->classifiedService->createRating($classified, $request->all());

        $rating_avg = round($classified->ratings->pluck('star')->avg());
        $total_rating = $classified->ratings->count();
        $html = view('components.common.detail.review-result', [
            'item' => $classified,
            'rating_avg' => $rating_avg,
            'total_rating' => $total_rating,
            'old_rating' => $request->star
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá thành công',
            'data' => [
                'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
            ]
        ], 200);
    }

    // cập nhật bình luận tin đăng
    public function update_comment_classified($comment_id, Request $request)
    {
        if (!Auth::guard('user')->check()) {
            return response()->json('Vui lòng đăng nhập', 401);
        }
        if (strlen($request->comment_content)>200) {
            return response()->json('Bình luận chỉ được tối đa 200 ký tự', 400);
        }
        $comment = ClassifiedComment::find($comment_id);
        $comment->comment_content = $request->comment_content;
        $comment->save();
        // response return
        return response()->json(['success'=>'Cập nhật thành công','comment_id' => $comment->id,'comment_content'=>$comment->comment_content], 200);
    }
    // xoá bình luận tin đăng
    public function delete_comment_classified($comment_id, Request $request)
    {
        if (!Auth::guard('user')->check()) {
            return response()->json('Vui lòng đăng nhập', 401);
        }

        $comment = ClassifiedComment::find($comment_id);
        if(!$comment){
            return response()->json('Đã xoá bình luận này',403);
        }
        $comment->child()->delete();
        $comment->delete();
        return response()->json(['success'=>'Xoá thành công','comment_id' => $comment_id], 200);
    }
    // like comment
    public function like_comment($comment_id)
    {
        if (!Auth::guard('user')->check()) {
            return response()->json('Vui lòng đăng nhập', 401);
        }
        $user_id = Auth::guard('user')->id();
        $value = '';
        if (!DB::table('classified_like_comment')->where('user_id', $user_id)->where('comment_id', $comment_id)->first()) {
            $value = 'like';
            DB::table('classified_like_comment')->insert([
                'user_id' => $user_id,
                'comment_id' => $comment_id,
            ]);
            return response()->json($value, 200);
        }
        $value = 'unlike';
        DB::table('classified_like_comment')->where([
            'user_id' => $user_id,
            'comment_id' => $comment_id,
        ])->delete();
        return response()->json($value, 200);
    }
    // report comment
    public function  report_comment(ReportCommentRequest $request, $comment_id)
    {
        // kiểm tra đăng nhập
        if (!Auth::guard('user')->check()) {
            Toastr::error('Vui lòng đăng nhập');
            return back();
        }

        $user = Auth::guard('user')->user();
        $comment = ClassifiedComment::findOrFail($comment_id);

        $reported = $comment->reports()
            ->firstWhere('user_id', $user->id);
        if($reported) {
            Toastr::error('Mỗi tài khoản chỉ được báo cáo 1 lần');
            return back();
        }

        $request['user_id'] = $user->id;
        $request['report_position'] = 2;
        $this->classifiedService->createReport($comment, $request->all());

        Toastr::success('Báo cáo thành công');
        return back();
    }
    // report content
    public function  report_content(ClassifiedReportRequest $request, $classified_id)
    {
        // kiểm tra đăng nhập
        if (!Auth::guard('user')->check()) {
            Toastr::error('Vui lòng đăng nhập');
            return back();
        }

        $user = Auth::guard('user')->user();
        $classified = Classified::findOrFail($classified_id);

        $reported = $classified->reports()
            ->firstWhere('user_id', $user->id);
        if($reported) {
            Toastr::error('Mỗi tài khoản chỉ được báo cáo 1 lần');
            return back();
        }

        $request['user_id'] = $user->id;
        $request['report_position'] = 1;
        $report = $this->classifiedService->createReport($classified, $request->all());

        Toastr::success('Báo cáo thành công');
        return $report->classified->is_block
            ? redirect(route('home.index'))
            : back();
    }

    # convert text
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

    // share tin đăng
    public function share_classified($classified_id, Request $request)
    {
        if($request->ajax()){
            $classified = Classified::find($classified_id);
            $classified->num_share++;
            $classified->save();
            return response()->json(['success'=>'Cập nhật thành công'], 200);
        }
    }
    public function customer_classified($user_id, CustomerClassifiedRequest $request)
    {
            $user = User::find($user_id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gửi thông tin không thành công',
                ], 403);
            }

            $customer_data = [
                'user_id' => $user_id,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'classified_id' => $request->classified_id,
            ];
            $check_customer = Customer::where($customer_data)->first();
            if($check_customer){
                return response()->json([
                    'success' => false,
                    'message' => 'Email và số điện thoại này đã gửi thông tin rồi',
                ], 401);
            }

            $checkLimitCustomer = $this->customerService->checkLimitCustomers($user);

            if ($checkLimitCustomer && !data_get($checkLimitCustomer, 'success')) {
                return response()->json([
                    'success' => false,
                    'message' => data_get($checkLimitCustomer, 'message'),
                ], 401);
            }

            $customer_data['fullname'] = $request->fullname;
            $customer_data['note'] = $request->note;
            $customer_data['created_at'] = time();
            DB::table('customer')->insert($customer_data);

            return response()->json([
                'success' => true,
                'message' => 'Gửi thông tin thành công',
            ], 200);
    }

    public function sendAdvisory($id, SendAdvisoryRequest $request)
    {
        $classified = Classified::showed()->find($id);

        if (!$classified || !$classified->user && !$classified->contact_email)
            return response()->json([
                'success' => false,
                'message' => 'Gửi thông tin không thành công',
            ], 403);

        if($classified->contact_email) {
            $ip = $request->ip();
            $userId = Auth::guard('user')->id();
            $checkedData = [
                'ip' => $ip,
                'user_id' => $userId,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
            ];

            $checkLimitDayAdvisories = $this->advisoryService->checkAdded($classified, $checkedData);

            if ($checkLimitDayAdvisories && !data_get($checkLimitDayAdvisories, 'success')) {
                return response()->json([
                    'success' => false,
                    'message' => data_get($checkLimitDayAdvisories, 'message'),
                ], 422);
            }

            // advisory
            $advisoryData = [
                'fullname' => $request->fullname,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'note' => $request->note,
                'ip' => $ip,
                'user_id' => $userId,
                'options' => [
                    'registration' => [
                        'url' => $classified->getShowUrl(),
                        'name' => $classified->classified_name,
                    ]
                ]
            ];
            $advisory = $this->advisoryService->create($classified, $advisoryData);
            // end advisory

            SendAdvisoryJob::dispatch($advisory->id);

            return response()->json([
                'success' => true,
                'message' => 'Gửi thông tin thành công',
            ], 200);
        }

        $addedCustomer = $classified->user
            ->customers()
            ->firstWhere([
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'classified_id' => $id,
            ]);

        if($addedCustomer){
            return response()->json([
                'success' => false,
                'message' => 'Email và số điện thoại này đã gửi thông tin rồi',
            ], 401);
        }

        $checkLimitCustomer = $this->customerService->checkLimitCustomers($classified->user);

        if ($checkLimitCustomer && !data_get($checkLimitCustomer, 'success')) {
            return response()->json([
                'success' => false,
                'message' => data_get($checkLimitCustomer, 'message'),
            ], 401);
        }

        $request['classified_id'] = $id;
        $this->customerService->create($classified->user, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Gửi thông tin thành công',
        ], 200);
    }

    public function provinceClassified(Request $request, $provinceUrl)
    {
        $province = $this->provinceService->getProvinceFromUrl($provinceUrl);

        if (!$province) return abort(404);

        // get group nha-dat (nha-dat-ban, nha-dat-cho-thue)
        $buyGroup = $this->groupService->getGroupFromUrl('nha-dat-ban');
        $sellGroup = $this->groupService->getGroupFromUrl('nha-dat-cho-thue');

        $groupIds = $buyGroup->getChildrenGroupIds();
        $groupIds = array_unique(array_merge($groupIds, $sellGroup->getChildrenGroupIds()));

        $request['group_ids'] = $groupIds;
        $request['province_id'] = $province->id;
        $classifieds = $this->classifiedService->getListFromQuery($request->all());

        return view('Home.Classified.province', [
            'classifieds' => $classifieds,
            'province' => $province,
        ]);
    }
}
