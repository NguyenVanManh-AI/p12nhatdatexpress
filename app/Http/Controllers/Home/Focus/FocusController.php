<?php

namespace App\Http\Controllers\Home\Focus;

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

        if ($group->id == 48){
            // return to event list route
            return redirect(route('home.event.list'));

            // $request['is_highlight'] = 1;
            // return (new EventController)->list($request);
        }

        $itemsPerPage = config('constants.focus-news.child.items_per_page', 18);

        $queries = $request->all();
        $queries['group_id'] = $group->id;
        $queries['items_per_page'] = $itemsPerPage;
        $news = $this->focusService->getListFromQuery($queries);

        return view('Home.FocusNews.Children', [
            'group' => $group,
            'news' => $news,
            'itemsPerPage' => $itemsPerPage
        ]);
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
            ->showed()
            ->leftJoin('group', 'group.id', '=', 'news.group_id')
            ->leftJoin('admin', 'admin.id', '=', 'news.created_by')
            ->firstOrFail();

        $focus->update([
            'num_view' => $focus->num_view + 1,
        ]);

        $userId = Auth::guard('user')->id();
        $liked = $focus->likes()->where('user.id', $userId)->exists();

        return view('Home.FocusNews.DetailFocusNew', [
            'focus' => $focus,
            'liked' => $liked
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
            ->showed()
            ->where('news.is_express', 0)
            ->orderBy('news.is_highlight', 'desc')
            ->latest('news.renew_at')
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
}
