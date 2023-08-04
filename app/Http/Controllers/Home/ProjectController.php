<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\Project\ProjectReportRequest;
use App\Http\Requests\Home\Project\RequestProjectRequest;
use App\Http\Requests\Project\CommentRequest;
use App\Http\Requests\Project\RatingRequest;
use App\Http\Requests\Project\ReportCommentRequest;
use App\Http\Requests\Project\RequestUpdateRequest;
use App\Http\Resources\Project\CommentResource;
use App\Models\Admin\ProjectLocation;
use App\Models\Progress;
use App\Models\Project;
use App\Models\ProjectComment;
use App\Models\Utility;
use App\Services\GroupService;
use App\Services\ProjectService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProjectController extends Controller
{
    private const DU_AN_GROUP_URL = 'du-an';
    private const NUM_CAN_UPDATED = 20;
    private const MILLION = 1000000;
    private const BILLION = 1000000000;
    private const MAX_KEYWORD_SPECIAL = 8;
    private const MAX_KEYWORD_LINK = 18;

    private GroupService $groupService;
    private ProjectService $projectService;

    public function __construct()
    {
        $this->groupService = new GroupService;
        $this->projectService = new ProjectService;
    }

    // public function Rating($id,Request $request){
    //     //kiếm tra ip đánh giá được 1 lần/ 1 bài mỗi ngày
    //     $checkIp = DB::table('project_rating')
    //     ->where('project_id',$id)
    //     ->where('ip',$request->ip)
    //     ->where('rating_time','>',strtotime(Carbon::now()->addDay(-1)))
    //     ->count();
    //     if($checkIp ==0){
    //         DB::table('project_rating')->insert(
    //             [
    //                 'one_star'=>$request->onestar,
    //                 'two_star'=>$request->twostar,
    //                 'three_star'=>$request->threestar,
    //                 'four_star'=>$request->fourstar,
    //                 'five_start'=>$request->fivestar,
    //                 'rating_time'=>time(),
    //                 'ip'=>$request->ip,
    //                 'project_id'=>$id
    //             ]
    //         );
    //         return back();
    //     }
    // }

    # Chi tiết dự án
    public function projectDetail($slug)
    {
        $project = Project::with('location.district:id,district_name,district_type,province_id')
            ->leftJoin('project_location', 'project_location.project_id', 'project.id')
            ->leftJoin('province', 'province.id', 'project_location.province_id')
            ->leftJoin('district', 'district.id', 'project_location.district_id')
            ->leftJoin('unit', 'unit.id', 'project.price_unit_id')
            ->leftJoin('group', 'group.id', 'project.group_id')
            ->leftJoin('project_param', 'project_param.id', 'project.project_juridical')
            ->leftJoin('progress', 'progress.id', 'project.project_progress')
            ->leftJoin('furniture', 'furniture.id', 'project.project_furniture')
            ->leftJoin('direction', 'direction.id', 'project.project_direction')
            ->leftJoin('admin', 'project.created_by', '=', 'project.created_by')
            ->where('project.project_url', '=', $slug)
            ->with('location.province', 'location.district', 'unit_area')
            ->select('project.*', 'project.id', 'project.image_url', 'project.project_name', 'project.project_price', 'project.project_price_old', 'project.project_scale', 'project.num_view', 'project.project_content',
                'project.location_descritpion', 'project.utility_description', 'project.ground_description', 'project.legal_description', 'project.payment_description', 'unit.unit_name', 'province.province_name',
                'district.district_name', 'group.group_name', 'project.investor', 'progress.progress_name', 'project.project_rent_price', 'project.project_area_from', 'project.project_area_to',
                'project_param.param_name', 'furniture.furniture_name', 'direction.direction_name', 'project.bank_sponsor', 'project.project_road', 'project.created_at', 'project.list_utility', 'project.video',
                'project_location.map_latitude', 'project_location.map_longtitude', 'price_unit_id', 'area_unit_id', 'project.group_id', 'admin.admin_fullname as author',
                'project_progress', 'project.meta_title', 'project.meta_desc')
            ->showed()
            ->first();
        // icon chi tiết dự án
        $icon_project = DB::table('project_config')
            ->where('type_config',1)
            ->select('id','image','description')
            ->get();
        //comment
        $comment = ProjectComment::with('user_detail:id,fullname,image_url', 'admin:id,admin_fullname,image_url')
            ->leftJoin('project', 'project.id', '=', 'project_comment.project_id')
            ->leftJoin('project_rating', function ($leftJoin) {
                $leftJoin->on('project_comment.user_id', '=', 'project_rating.user_id')
                    ->on('project_rating.project_id', '=', 'project.id');
            })->where([
                'project_comment.parent_id' => null,
                'project_comment.is_show' => 1,
                'project_comment.project_id' => $project->id,

            ])
            ->orderBy('project_comment.created_at', 'desc')
            ->select('project_comment.*',
                'project_rating.star')
            ->withCount('children', 'likes')
            ->paginate(5);
        
        // tiện ích
        $projectUtilities = json_decode($project->list_utility) ?: [];
        $utilities = Utility::whereIn('id', $projectUtilities)
            ->showed()
            ->oldest('show_order')
            ->get();

        //tăng view
        DB::table('project')->where('project.project_url', '=', $slug)->limit(1)->update(['num_view' => $project->num_view + 1,]);
        $properties = DB::table('properties')->where('properties_type', 1)->get();
        $key_search = [
            [
              'type' => 0,
              'title' => 'Dự án ' . $project->group_name . ' ' . $project->location->district->district_name
            ],
            [
                'type' => 1,
                'title' => 'Dự án ' . $project->group_name . ' ' . $project->province_name,
            ],
            [
                'type' => 0,
                'title' => $this->convert_string('Dự án ' . $project->group_name. ' ' . $project->location->district->district_name)
            ],
            [
                'type' => 1,
                'title' => $this->convert_string('Dự án ' . $project->group_name . ' ' . $project->province_name)
            ],
        ];

        return view('Home.Project.ProjectDetail', compact([
            'project', 'properties', 'utilities', 'slug', 'comment', 'key_search','icon_project',
        ]));
    }

    # Vote
    public function Vote($id,Request $request){
    $validate = $request->validate([
        'survey_1' => 'required',
        'survey_2' => 'required',
        'survey_3' => 'required',
        'survey_4' => 'required',
        'survey_5' => 'required',
        'survey_6' => 'required',
    ]);
    $data = $request->all();
    $i=0;
    // id user
        $id_user = Auth::guard('user')->id();
    foreach ($data as $value) {
        if($i>0){
            $check = DB::table('project_servey')
                ->where('user_id',$id_user)
                ->where('project_param_id',$i)->first();
            if($check){
            // đã đánh giá
                DB::table('project_servey')->where('user_id',$id_user)
                    ->where('project_param_id',$i)->update(array(
                    'project_id'        =>$id,
                    'user_id'           =>$id_user,
                    'project_param_id'  => $i,
                    'servey_time'       =>time(),
                    'is_verify'         =>$value
                ));
            }
            else {
                // chưa đánh giá
                DB::table('project_servey')->insert(array(
                    'project_id'        =>$id,
                    'user_id'           =>$id_user,
                    'project_param_id'  => $i,
                    'servey_time'       =>time(),
                    'is_verify'         =>$value
                ));
            }


        }
        $i++;
    }
        $vote = DB::table('project_servey')->where('project_id',$id)
            ->select('project_param_id',
                DB::raw('count(IF(is_verify = 0, 1, NULL)) as total_2 '),
                DB::raw('count(IF(is_verify = 1, 1, NULL)) as total_1 '),
            )
            ->groupBy('project_param_id')
            ->get();
        $array =array();
        foreach ($vote as $item){

            $item_array = [$item->project_param_id,$item->total_1,$item->total_2];
            array_push($array,$item_array);
        }
        DB::table('project')
            ->where('project.id',$id)
            ->update([
            'project.project_servey'=>serialize($array)
        ]);


    return back();
}

    public function index(Request $request, $group_url = null)
    {
        $group = $this->groupService->getGroupFromUrl(self::DU_AN_GROUP_URL, $group_url);

        if (!$group) return abort(404);

        $request['group_ids'] = $group->getChildrenGroupIds();
        $request['load_individual'] = true;
        $projects = $this->projectService->getListFromQuery($request->all());

        $progress = Progress::get();

        $provinces = get_cache_province();
        $districts = get_cache_district();

        $structure_special = $group && $group_url ? "Dự án " . $group->group_name . " "  : "Dự án ";
        $districts_special = ProjectLocation::with('district:id,district_name,district_url,province_id,district_type')
            ->select(DB::raw('count(*) as count'), 'district_id')
            ->groupBy('district_id')
            ->havingRaw('count > ?', [0])
            ->orderBy('count', 'desc')
            ->take(self::MAX_KEYWORD_SPECIAL);

        if ($group && $group_url)
            $districts_special
                ->join('project', 'project.id', '=', 'project_location.project_id')
                ->where('project.group_id', '=', $group->id);

        $districts_special = $districts_special->get();

        return view('Home.Project.Index', [
                'projects' => $projects,
                'progress' => $progress,
                'group' => $group,
                'provinces' => $provinces,
                'districts' => $districts,
                'districts_special' => $districts_special, 'structure_special' => $structure_special,
                'max_link' => self::MAX_KEYWORD_LINK
            ]);
    }

    /**
     * get search projects, nearly, expert.. for paradigm
     * @param string $group_url
     * @param string $group_child
     *
     * @return JsonResponse
     */
    public function searchProject($groupUrl = null, Request $request): JsonResponse
    {
        $group = $this->groupService->getGroupFromUrl(self::DU_AN_GROUP_URL, $groupUrl);

        if (!$group) {
            return response()->json([
                'success' => false,
            ]);
        }

        // get list projects
        $request['group_ids'] = $group->getChildrenGroupIds();
        $request['load_individual'] = true;
        $projects = $this->projectService->getListFromQuery($request->all());

        $html = view('components.news.project.search-results', [
            'group' => $group,
            'projects' => $projects,
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
     * get more items
     * @param string $groupUrl
     * @param string $childUrl = null
     *
     * @return Response
     */
    public function getMoreItems($groupUrl, $childUrl = null, Request $request)
    {
        $group = $this->groupService->getGroupFromUrl($groupUrl, $childUrl);

        if (!$group) {
            return response()->json([
                'success' => false,
            ]);
        }

        // get list projects
        $request['group_ids'] = $group->getChildrenGroupIds();
        $projects = $this->projectService->getListFromQuery($request->all());

        $html = '';
        foreach ($projects as $item) {
            $html .= view('components.news.project.item', [
                'item' => $item,
                'watched_classified' => getWatchedClassifieds(),
            ])->render();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
                'onLastPage' => $projects->onLastPage()
            ]
        ]);
    }

public function viewMap($id)
{
    $getLocation = DB::table('project_location')->where('project_id',$id)->first();

    $voteYes1= DB::table('project_servey')->where('project_id',$id)->where('project_param_id',11)->where('is_verify',1)->count();

    $voteYes2= DB::table('project_servey')->where('project_id',$id)->where('project_param_id',12)->where('is_verify',1)->count();
    $voteYes3= DB::table('project_servey')->where('project_id',$id)->where('project_param_id',13)->where('is_verify',1)->count();
    $voteYes4= DB::table('project_servey')->where('project_id',$id)->where('project_param_id',14)->where('is_verify',1)->count();
    $voteYes5= DB::table('project_servey')->where('project_id',$id)->where('project_param_id',15)->where('is_verify',1)->count();
    $voteYes6= DB::table('project_servey')->where('project_id',$id)->where('project_param_id',16)->where('is_verify',1)->count();

    $voteNo1= DB::table('project_servey')->where('project_id',$id)->where('project_param_id',11)->where('is_verify',0)->count();
    $voteNo2= DB::table('project_servey')->where('project_id',$id)->where('project_param_id',12)->where('is_verify',0)->count();
    $voteNo3= DB::table('project_servey')->where('project_id',$id)->where('project_param_id',13)->where('is_verify',0)->count();
    $voteNo4= DB::table('project_servey')->where('project_id',$id)->where('project_param_id',14)->where('is_verify',0)->count();
    $voteNo5= DB::table('project_servey')->where('project_id',$id)->where('project_param_id',15)->where('is_verify',0)->count();
    $voteNo6= DB::table('project_servey')->where('project_id',$id)->where('project_param_id',16)->where('is_verify',0)->count();
    $project_id = $id;

    $html = view('Home.Project.PopupProject',compact('getLocation','voteYes1','voteYes2','voteYes3','voteYes4','voteYes5','voteYes6','voteNo1','voteNo2','voteNo3','voteNo4','voteNo5','voteNo6','project_id'))->render();

    return response()->json([
        'success' => true,
        'data' => [
            'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
            'location' => $getLocation,
        ]
    ], 200);
}

    public function rating($project_id, RatingRequest $request)
    {
        $project = Project::showed()->find($project_id);

        if(!$project){
            Toastr::error('Không tồn tại dự án');
            return back();
        }

        $user = Auth::guard('user')->user();
        $request['user_id'] = data_get($user, 'id');
        $request['ip'] = $request->ip();
        $this->projectService->createRating($project, $request->all());

        $rating_avg = round($project->ratings->pluck('star')->avg());
        $total_rating = $project->ratings->count();
        $html = view('components.common.detail.review-result', [
            'item' => $project,
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

    // bình luận dự án
    public function addComment($projectId, CommentRequest $request)
    {
        $user = Auth::guard('user')->user();

        $project = Project::find($projectId);
        $this->authorizeForUser($user, 'comment', $project);

        $data = [
            'content' => $request->content,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
        ];

        $projectComment = $this->projectService->addComment($project, $data);
        $rating = $project->ratings()
            ->firstWhere([
                'user_id' => $user->id
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Bình luận thành công',
            'data' => [
                'comment' => (new CommentResource($projectComment))->resolve([]),
                'user_rating' => data_get($rating, 'star', 0)
            ]
        ], 200);
    }

    // xoá bình luận dự án
    public function delete_comment_project($comment_id, Request $request)
    {
        if (!Auth::guard('user')->check()) {
            return response()->json('Vui lòng đăng nhập', 401);
        }

        $comment = ProjectComment::find($comment_id);
        if(!$comment){
            return response()->json('Đã xoá bình luận này',403);
        }
        $comment->children()->delete();
        $comment->delete();
        return response()->json(['success'=>'Xoá thành công','comment_id' => $comment_id], 200);
    }
    // like comment
    public function like_comment($comment_id){
        if(!Auth::guard('user')->check()){
            return response()->json('Vui lòng đăng nhập',401);
        }
        $user_id = Auth::guard('user')->id();
        $value = '';
        if(!DB::table('project_like_comment')->where('user_id',$user_id)->where('comment_id',$comment_id)->first()){
            $value = 'like';
            DB::table('project_like_comment')->insert([
                'user_id'=>$user_id,
                'comment_id'=>$comment_id,
            ]);
            return response()->json($value,200);
        }
        $value = 'unlike';
        DB::table('project_like_comment')->where([
            'user_id'=>$user_id,
            'comment_id'=>$comment_id,
        ])->delete();
        return response()->json($value,200);
    }
    // report content
    public function  report_content(ProjectReportRequest $request,$project_id){
        // kiểm tra đăng nhập
        if(!Auth::guard('user')->check()){
            Toastr::error('Vui lòng đăng nhập');
            return back();
        }

        $user = Auth::guard('user')->user();
        $project = Project::findOrFail($project_id);

        $reported = $project->reports()
            ->firstWhere('user_id', $user->id);
        if($reported) {
            Toastr::error('Mỗi tài khoản chỉ được báo cáo 1 lần');
            return back();
        }

        $request['user_id'] = $user->id;
        $request['report_position'] = 1;
        $report = $this->projectService->createReport($project, $request->all());

        Toastr::success('Báo cáo thành công');
        return $report->project->is_block
            ? redirect(route('home.index'))
            : back();
    }

    // report comment
    public function  report_comment(ReportCommentRequest $request, $comment_id)
    {
        // kiểm tra đăng nhập
        if(!Auth::guard('user')->check()){
            Toastr::error('Vui lòng đăng nhập');
            return back();
        }

        $user = Auth::guard('user')->user();
        $comment = ProjectComment::findOrFail($comment_id);

        $reported = $comment->reports()
            ->firstWhere('user_id', $user->id);
        if($reported) {
            Toastr::error('Mỗi tài khoản chỉ được báo cáo 1 lần');
            return back();
        }

        $request['user_id'] = $user->id;
        $request['report_position'] = 2;
        $this->projectService->createReport($comment, $request->all());

        Toastr::success('Báo cáo thành công');
        return back();
    }

    // update project
    public function update_project(RequestUpdateRequest $request, $project_id)
    {
        $choose = (int)$request->update;
        $new = $request->update_content;
        $project = Project::find($project_id);
        $type = 0;

        $data_insert_new = [
            'num' => 1,
            'date' => time(),
            'confirm' => 0,
            'change_date' => null,
            'users' => [
              auth('user')->id()
            ],
        ];

        if ($choose && $project){
            switch ($choose){
                case 1:
                    $update_price = unserialize($project->update_price);
                    $old_price = $project->project_price_new ?? $project->project_price;
                    $array_collect = collect($update_price)->where('price', $new)->where('price_old', $old_price);

                    $type = $this->update_project_data($old_price, $new, $array_collect, $project, 'project_price','project_price_old', 'update_price');
                    if ($type == 2){
                        $data = array_merge($data_insert_new, [
                                'price' => (int) $new,
                                'price_old' => (int) $old_price,
                        ]);
                        $update_price[] = $data;

                        $project->update_price = serialize($update_price);
                    }
                    break;
                case 2:
                    // update gia thue
                    $update_rent_price = unserialize($project->update_rent_price);
                    $old_rent_price = $project->project_rent_price_new ?? $project->project_rent_price;
                    $array_collect = collect($update_rent_price)->where('price', $new)->where('price_old', $old_rent_price);

                    $type = $this->update_project_data($old_rent_price, $new, $array_collect, $project, 'project_rent_price','project_rent_price_old', 'update_rent_price');
                    if ($type == 2){
                        $data = array_merge($data_insert_new, [
                            'price' => (int) $new,
                            'price_old' => (int) $update_rent_price,
                        ]);
                        $update_price[] = $data;

                        $project->update_rent_price = serialize($update_price);
                    }
                    break;

                case 3:
                    // update tinh trang
                    $new = $request->update_content_progress;

                    $update_progress = unserialize($project->update_progress);
                    $old_progress = $project->project_progress;
                    $array_collect = collect($update_progress)->where('id_progress', $new)->where('id_progress_old', $old_progress);

                    $type = $this->update_project_data($old_progress, $new, $array_collect, $project, 'project_progress','project_progress_old', 'update_progress');
                    if ($type == 2){
                        $data = array_merge($data_insert_new, [
                            'id_progress' => (int) $new,
                            'id_progress_old' => (int) $old_progress,
                        ]);
                        $update_progress[] = $data;

                        $project->update_progress = serialize($update_progress);
                    }
                    break;

            }

            if ($type > 0) {
                $project->save();
                Toastr::success("Đã gửi yêu cầu cập nhật");
            }

        }else{
            Toastr::error("Lỗi không xác định");
        }
        return back();
    }

    // request project
    public function request_project(RequestProjectRequest $request){
        $data = [
            'project_name' => $request->project_name,
            'investor' => $request->investor,
            'address' => $request->address,
            'ward_id' => $request->ward_id,
            'district_id' => $request->district_id,
            'province_id' => $request->province_id
        ];
        $is_exists = DB::table('project_request')->where($data)->count();
        if ($is_exists == 0) {
            $data['created_at'] = time();
            $user = auth('user')->user();
            if ($user)
                $data['user_id'] = $user->id;

            DB::table('project_request')->insert($data);
            Toastr::success("Yêu cầu dự án thành công");
        }
        else{
            Toastr::success("Yêu cầu dự án đang được xem xét");
        }
        return back();
    }

    #
    private function update_project_data($old, $new, $array_collect, $project, $column_new, $column_old, $column_update): int
    {
        // 0: don't change
        // 1: update exists
        // 2: create a new item
        $type = 0;
        $user_id = auth('user')->id();

        if ($old != $new && $user_id) {
            if ($array_collect->count() > 0) {

                $change_date = null;

                $is_exists = $array_collect->filter(function($collection) use($user_id){
                    return in_array($user_id, $collection['users']);
                })->count();

                if ($is_exists > 0){
                    Toastr::error("Yêu cầu cập nhật dự án của bạn đang được xem xét. Vui lòng đợi");
                    return 0;
                }

                if ($array_collect[$array_collect->keys()[0]]['num'] + 1 >= self::NUM_CAN_UPDATED && $array_collect[$array_collect->keys()[0]]['confirm'] == 0){
                    $change_date = time();
                }

                $array_collect = $array_collect->map(function ($item, $key) use ($change_date) {
                    if ($key == 0) {
                        $item['date'] = time();
                        if ($change_date) {
                            $item['change_date'] = time();
                        }
                            $item['num']++;
                    }
                    return $item;
                })->toArray();

                $type = 1;

                if ($change_date){
                    $project->$column_new = $new;
                    $project->$column_old = $old;
                }

                $project->$column_update = serialize($array_collect);

            } else {
                $type = 2;
            }

        }
        return $type;
    }

    # sort project
    private function sort_project($request, $query)
    {
        if (isset($request->sort)) {
            if ($request->sort == 'luot-xem-nhieu-nhat') {
                $query = $query->orderBy('project.num_view', 'desc');
            }
            if ($request->sort == 'gia-cao-nhat') {
                $query = $query->orderBy('price_project_real', 'desc');
            }
            if ($request->sort == 'gia-thap-nhat') {
                $query = $query->orderBy('price_project_real', 'asc');
            }
            if ($request->sort == 'dien-tich-lon-nhat') {
                $query = $query->orderBy('project.project_scale', 'desc');
            }
            if ($request->sort == 'dien-tich-nho-nhat') {
                $query = $query->orderBy('project.project_scale', 'asc');
            }
            if ($request->sort == 'binh-thuong') {
                $query = $query->orderBy('project.id', 'desc');
            }
        } else {
            $query = $query->orderBy('project.id', 'desc');
        }
        return $query;
    }

    # filter project
    private function filter_project($request, $query){
        if (isset($request->keyword)) {
            $query = $query->where('project.project_name', 'like', '%' . $request->keyword . '%');
        }

        if(Session::get('accept_location',0) == 1) {
            if (isset($request->district_id)) {
                $query = $query->where('project_location.district_id', $request->district_id);
            } else if ($request->province_id != '') {
                $query = $query->where('project_location.province_id', $request->province_id);
            }
        }

        if (isset($request->progress_id)) {
            $query = $query->where('project.project_progress', $request->progress_id);
        }
        if (isset($request->direction)) {
            $query = $query->where('project.project_direction', $request->direction);
        }
        if (isset($request->furniture)) {
            $query = $query->where('project.project_furniture', $request->furniture);
        }
        $request->bank_sponsor ? $query->where('project.bank_sponsor', 1) : null;
        $request->ecommerce ? $query->where('list_utility', 'LIKE', '%4%') : null;
        $request->parking ? $query->where('list_utility', 'LIKE', '%1%') : null;
        $request->gym ? $query->where('list_utility', 'LIKE', '%2%') : null;
        $request->park ? $query->where('list_utility', 'LIKE', '%5%') : null;
        $request->spa ? $query->where('list_utility', 'LIKE', '%6%') : null;
        $request->pool ? $query->where('list_utility', 'LIKE', '%9%') : null;
        $request->kindergarten ? $query->where('list_utility', 'LIKE', '%3%') : null;
        // $district_location = Session::get('district', null);

//        if ($district_location) {
//
//            $query = $query->where('project_location.district_id', '=', $district_location);
//        }

        if (isset($request->area)) {

            $area = explode('-', $request->area);
            if ($area[0] != 0 && $area[1] != 0) {
                $query = $query->whereBetween('project.project_scale', [$area[0], $area[1]]);

            } elseif ($area[0] == 0) {
                $query = $query->where('project.project_scale', '<=', $area[1]);
            } else {
                $query = $query->where('project.project_scale', '>=', $area[0]);
            }
        }
        if ($request->priceBillion) {
            $price = explode('-', $request->priceBillion);
            if ($price[0] != 0 && $price[1] != 0) {
                $query = $query->havingRaw('price_project_real >=' . $price[0] * self::BILLION);
                $query = $query->havingRaw('price_project_real <=' . $price[1] * self::BILLION);
            } elseif ($price[0] == 0) {
                $query = $query->havingRaw('price_project_real <=' . $price[1] * self::BILLION);
            } else {
                $query = $query->havingRaw('price_project_real >=' . $price[0] * self::BILLION);
            }
        }

        return $query;
    }

    # convert text
    function convert_string($str) {
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
