<?php

namespace App\Http\Controllers\Home\Focus;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\News;
use App\Services\FocusService;
use App\Traits\Filterable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FocusController extends Controller
{
    use Filterable;

    private FocusService $focusService;

    public function __construct()
    {
        $this->focusService = new FocusService;
    }

    /**
     * Array filter
     * @var string[]
     */
    protected $filterable = [
      'keyword'
    ];

    /**
     * Filter keyword
     * @param $query
     * @param $value
     * @return mixed
     */
    protected function filterKeyword($query, $value)
    {
        return $query->where($this->table . '.' . 'news_title', 'like', "%$value%");
    }

    /**
     * Table
     * @var string
     */
    protected $table = 'news';

    /**
     * Group parent id
     * @var int
     */
    protected $group_id = 47;

    /*-------------------------------------LIST----------------------------------------------------*/
    /**
     * Index focus
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function list(Request $request){
        $group = DB::table('group')->where('id',47)->first();
        return view('Home.FocusNews.Index', compact('group'));
    }

    /**
     * Get list children
     * @param Request $request
     * @param $url_group
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function list_children(Request $request, $url_group)
    {
        $group = Group::where('group_url', $url_group)->firstOrFail();

        $list = News::showed()
            ->latest();

        if ($group->id == 49){
            $list = $list->where('is_express', 1);
        }else if ($group->id == 48){
            // return to event list route
            return redirect(route('home.event.list'));

            // $request['is_highlight'] = 1;
            // return (new EventController)->list($request);
        }else{
            $list = $list->where('group_id', $group->id)
                ->latest('is_express')
                ->latest('is_highlight');
        }

        $params = Helper::array_remove_null($request->all());
        $list = $this->scopeFilter($list, $params);
        $list = $list->paginate(config('constants.home.num_paginate_focus'));

        $num_collection = collect(['num_cur' => $list->currentPage() * config('constants.home.num_paginate_focus')]);

        return view('Home.FocusNews.Children', compact('group', 'list', 'num_collection'));
    }

    /*-------------------------------------DETAIL----------------------------------------------------*/
    /**
     * Focus Detail
     * @param $focus_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function detail($group_url, $focus_url)
    {
        $focus = News::select('news.*', 'group.id as group_id', 'group.group_name', 'group.group_url', 'admin.admin_fullname')
            ->where('news.news_url', $focus_url)
            ->where('news.is_show', 1)
            ->leftJoin('group', 'group.id', '=', 'news.group_id')
            ->leftJoin('admin', 'admin.id', '=', 'news.created_by')
            ->firstOrFail();

        $focus->increment('num_view');

        $userId = Auth::guard('user')->id();
        $liked = $focus->likes()->where('user.id', $userId)->exists();

            // $has_like = DB::table('news_like')->where('user_id', auth('user')->id())
            //     ->where('news_id', $focus->id)->delete();

        return view('Home.FocusNews.DetailFocusNew', [
            'focus' => $focus,
            'liked' => $liked
        ]);
    }
    /*-------------------------------------LIKE----------------------------------------------------*/
    /**
     * Like toggle
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function ajax_toggle_like(Request $request, $id): JsonResponse
    {
        $user = Auth::guard('user')->user();
        $focusNews = News::select('news.*')
            ->showed()
            ->find($id);

        $liked = $this->focusService->like($focusNews, $user);

        return response()->json([
            'success' => true,
            'message' => ($liked ? 'Thích' : 'Bỏ thích') . ' thành công',
            'data' => [
                'liked' => $liked,
            ]
        ], 200);

        // $status = $this->toggle_like_news($id) ? 'success' : 'error';
        // $total = News::where('id', $id)->select('num_like', 'num_dislike')->first();
        // return response()->json(compact('status', 'total'));
    }

    /**
     * Dislike toggle
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function ajax_toggle_dislike(Request $request, $id): JsonResponse
    {
        $status = $this->toggle_dislike_news($id) ? 'success' : 'error';
        $total = News::where('id', $id)->select('num_like', 'num_dislike')->first();
        return response()->json(compact('status', 'total'));
    }

    /*-------------------------------------AJAX----------------------------------------------------*/
    /**
     * Ajax auto load page
     * @param Request $request
     * @param $group_id
     * @return JsonResponse
     */
    public function ajax_list(Request $request, $group_id){
        $list = News::where('group_id', $group_id)
            ->showed()
            ->orderBy('is_express', 'desc')
            ->orderBy('is_highlight', 'desc')
            ->orderBy('id', 'desc')
            ->offset($request->num_cur)
            ->take(config('constants.home.num_paginate_focus'))
            ->get();

        $html = '';
        foreach ($list as $property){
            $html .= view('components.home.focus.property-item', compact('property'))->render();
        }

        return response()->json([
            'num' => config('constants.home.num_paginate_focus'),
            'html' => $html
        ]);
    }
    /**
     * Ajax auto load page
     * @param Request $request
     * @param $group_id
     * @return JsonResponse
     */
    public function ajax_new(Request $request){

        $list = News::select('news.*', 'group.id as group_id', 'group.group_name', 'group.group_url')
            ->showed()
            ->latest('news.created_at')
            ->leftJoin('group', 'group.id', '=', 'news.group_id')
            ->offset($request->num_cur)
            ->take(9)
            ->get();

        $html = '';
        foreach ($list as $new){
            $html .= view('components.home.focus.new-item', compact('new'))->render();
        }

        return response()->json([
            'num' => 9,
            'html' => $html
        ]);
    }

    /**
     * Ajax auto load page
     * @param Request $request
     * @param $group_id
     * @return JsonResponse
     */
    public function ajax_knowledge(Request $request, $group_id){
        $group = DB::table('group')->where('id', $group_id)->first();
        $list = News::select('news.*')
            ->where('news.group_id', $group_id)
            ->where('news.is_show', 1)
            ->where('news.is_express', 0)
            ->orderBy('news.is_highlight', 'desc')
            ->orderBy('news.created_at', 'desc')
            ->offset($request->num_cur)
            ->take(15)
            ->get();

        $html = '';
        foreach ($list as $item){
            $html .= view('components.home.focus.knowledge-item', compact('item', 'group'))->render();
        }

        return response()->json([
            'num' => 15,
            'html' => $html
        ]);
    }

    //----------------------------------------------SUPPORT METHOD----------------------------------------------------//
    /**
     * Like
     */
    public function toggle_like_news($news_id): bool
    {
        $focus_count = News::where('id', $news_id)->count();
        $result = false;

        if ($focus_count > 0){
            $user_id = auth('user')->id();
            // Check reaction
            $reaction = DB::table('news_like')->where(['user_id' => $user_id, 'news_id' => $news_id])->select('type')->first();
            // Reacted
            if ($reaction){
                try {
                    DB::beginTransaction();
                    // Status is disliked => Change to like
                    if ($reaction->type == 0){
                        DB::table('news_like')->where(['user_id' => $user_id, 'news_id' => $news_id])->update(['type' => 1]);
                        News::where('id', $news_id)->update([
                            'num_like' => DB::raw('num_like + 1'),
                            'num_dislike' => DB::raw('num_dislike - 1'),
                        ]);
                    }else{
                        // Status is Like => Unlike
                        News::where('id', $news_id)->where('num_like', '>=', 0)->update([
                            'num_like' => DB::raw('num_like - 1')
                        ]);
                        DB::table('news_like')->where('user_id', auth('user')->id())
                            ->where('news_id', $news_id)->delete();
                    }
                    $result = true;
                    DB::commit();
                }catch (\Exception $exception){
                    $result = false;
                    DB::rollBack();
                }
            }else{
                try {
                    News::where('id', $news_id)->increment('num_like');
                    DB::table('news_like')->insert([
                        'user_id' => auth('user')->id(),
                        'news_id' => $news_id,
                        'type' => 1
                    ]);
                    $result = true;
                    DB::commit();
                }catch (\Exception $exception){
                    $result = false;
                    DB::rollBack();
                }
            }
        }
        return $result;
    }

    /**
     * Dislike
     */
    public function toggle_dislike_news($news_id): bool
    {
        $focus_count = News::where('id', $news_id)->count();
        $result = false;

        if ($focus_count > 0){
            $user_id = auth('user')->id();
            // Check reaction
            $reaction = DB::table('news_like')->where(['user_id' => $user_id, 'news_id' => $news_id])->select('type')->first();
            // Reacted
            if ($reaction){
                try {
                    DB::beginTransaction();
                    // Status is liked => Change to dislike
                    if ($reaction->type == 1){
                        DB::table('news_like')->where(['user_id' => $user_id, 'news_id' => $news_id])->update(['type' => 0]);
                        News::where('id', $news_id)->update([
                            'num_like' => DB::raw('num_like - 1'),
                            'num_dislike' => DB::raw('num_dislike + 1'),
                        ]);
                    }else{
                        // Status is dislike => Un dislike
                        News::where('id', $news_id)->where('num_dislike', '>=', 0)->update([
                            'num_dislike' => DB::raw('num_dislike - 1')
                        ]);
                        DB::table('news_like')->where('user_id', auth('user')->id())
                            ->where('news_id', $news_id)->delete();
                    }
                    $result = true;
                    DB::commit();
                }catch (\Exception $exception){
                    $result = false;
                    DB::rollBack();
                }
            }else{
                try {
                    News::where('id', $news_id)->increment('num_dislike');
                    DB::table('news_like')->insert([
                        'user_id' => auth('user')->id(),
                        'news_id' => $news_id,
                        'type' => 0
                    ]);
                    $result = true;
                    DB::commit();
                }catch (\Exception $exception){
                    $result = false;
                    DB::rollBack();
                }
            }
        }
        return $result;
    }
}
