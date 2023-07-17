<?php

namespace App\View\Components\Admin\Sidebar;

use App\Models\Admin\Page;
use App\Models\User\UserPostComment;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Menu extends Component
{
    public $page_sidebar;
    public $notify;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->page_sidebar =  $this->get_page();
        $this->notify = $this->get_notify_menu();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.sidebar.menu');
    }

    /**
     * Get page
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get_page(): array
    {
        $user = Auth::guard('admin')->user();

        if ($user->admin_type != 1) {
            // Get roles
            $role_session = session()->get('role');
            if ($role_session == null){
                Toastr::warning('Vui lòng đăng nhập lại');
                Auth::guard('admin')->logout();
                session()->flush();
            }
            $role_detail = unserialize($role_session->role_content);

            // Get page from role
            $key = collect($role_detail)->keys();

            // Check if is customer care
            if ($user->is_customer_care && !in_array(28, (array)$key)){
                $key[] = 29;
            }

            $id_page_parent = Page::select(['page_parent_id'])
                ->where('page_parent_id', '<>', null)
                ->whereIn('id', $key)
                ->pluck('page_parent_id')->unique()->toArray();

            $parent_page = Page::select(['id'])
                ->whereNull('page_parent_id')
                ->whereIn('id', $key)
                ->pluck('id')->unique()->toArray();

            $id_page_parent = array_merge($id_page_parent, $parent_page);

            return Page::select(['id', 'page_name', 'page_icon', 'page_url','show_order'])
                ->whereIn('id', $id_page_parent)
                ->withCount('children')
                ->with('children', function ($query) use ($key){
                    $query->whereIn('id', $key)
                    ->orderBy('show_order');
                })
                ->where('page_parent_id', null)
                ->orderBy('show_order', 'asc')
                ->get()
                ->toArray();

        }
        else{ 
            return Cache::rememberForever('page_super_admin', function (){
                return Page::select(['id', 'page_name', 'page_icon', 'page_url','show_order'])
                    ->withCount('children')
                    ->with('children', function ($query){
                        $query->orderBy('show_order');
                    })
                    ->where('page_parent_id', null)
                    ->orderBy('show_order', 'asc')
                    ->get()
                    ->toArray();
            });
        }
    }

    /**
     * Get notify menu
     * @return array
     */
    public function get_notify_menu(): array
    {
        $update_controller = app('App\Http\Controllers\Admin\Project\UpdateManageController');
        $REPORT_POST = 1;
        $REPORT_COMMENT = 2;
        $REPORT_PERSONAL = 3;

        // Thành viên
        $params[10] = DB::table('user')->where(['is_confirmed' => 0, 'user_type_id' => 3])->count();
        $params[8] = $params[10];

        // Dự án
        $update_progress = $update_controller->get_pending_count(1);
        $update_rent = $update_controller->get_pending_count(2);
        $update_sell = $update_controller->get_pending_count(3);

        $params[14] = DB::table('project_request')->where('is_deleted', 0)->where('confirmed_status', 0)->count();
        $params[15] = $update_progress + $update_rent + $update_sell;
        $params[48] = DB::table('project_report')->where('confirm_status', 0)->count();
        $params[11] = $params[14] + $params[15] + $params[48];

        // Tin rao
        $params[32] = DB::table('classified')->where('confirmed_status', 0)->count();
        $params[53] = DB::table('classified_report')->where('confirm_status', 0)->count();
        $params[31] = $params[32] + $params[53];

        // Sự kiện
        $params[18] = DB::table('event')->where('is_confirmed', 0)->count();
        $params[19] = DB::table('event_report')->where('confirm_status', 0)->count();
        $params[17] = $params[18] + $params[19];

        // Nạp tiền
        $params[22] = DB::table('user_deposit')->where(['deposit_status' => 0, 'deposit_type' => 'C'])->count();
        $params[23] = DB::table('bill_service')->where('confirm_status', 0)->count();
        $params[21] = $params[22] + $params[23];

        // Gói tin
        $params[26] = DB::table('user_deposit')->where(['deposit_status' => 0, 'deposit_type' => 'I'])->count();
        $params[25] = $params[26];

        // Express
        $params[83] = DB::table('user_deposit as ud')
            ->leftJoin('banner', 'banner.transaction_id', '=', 'ud.user_transaction_id')
            ->where(['deposit_status' => 0, 'deposit_type' => 'B'])
            ->where('date_to', '>', time())
            ->count();
        $params[66] = $params[83];

        // Report
        $classified_comment_report = DB::table('classified_report')->where(['report_position' => $REPORT_COMMENT,  'confirm_status' => 0])->count();
        $project_comment_report = DB::table('project_report')->where(['report_position' => $REPORT_COMMENT,  'confirm_status' => 0])->count();
        $post_comment_report =  UserPostComment::query()
            ->select('user_post_comment.id')
            ->join('user_post_report as upr', 'upr.user_post_comment_id', 'user_post_comment.id')
            ->where(['report_position' => $REPORT_COMMENT,  'confirm_status' => 0, 'user_post_comment.is_deleted' => 0])
            ->groupBy('user_post_comment.id')
            ->count('*');
        $personal_report = DB::table('user')
            ->select('user.id')
            ->join('user_post_report as upr', 'upr.personal_id', '=', 'user.id')
            ->where(['report_position' => $REPORT_PERSONAL,  'confirm_status' => 0, 'user.is_deleted' => 0])
            ->groupBy('user.id')
            ->pluck('id')
            ->count();
        $post_report = DB::table('user_post')
            ->select('user_post.id')
            ->join('user_post_report', 'user_post_report.user_post_id', '=', 'user_post.id')
            ->where(['report_position' => $REPORT_POST,  'confirm_status' => 0, 'user_post.is_deleted' => 0])
            ->groupBy('user_post.id')
            ->count('*');

        # Tin dang
        $params[89] = DB::table('classified_report')->where(['report_position' => $REPORT_POST,  'confirm_status' => 0])->count();
        $params[90] =$classified_comment_report;

        # Du an
        $params[91] = DB::table('project_report')->where(['report_position' => $REPORT_POST,  'confirm_status' => 0])->count();
        $params[92] = $project_comment_report;

        # Trang ca nhan
        $params[93] = $personal_report;
        $params[94] = $post_report;
        $params[95] = $post_comment_report;

        # Su kien
        $params[96] = $params[19];
        $params[67] = $params[89] + $params[90] + $params[91] + $params[92] + $params[93] + $params[94] + $params[95] + $params[96];

        // Comment
        $params[58] = $project_comment_report;
        $params[59] = $classified_comment_report;
        $params[60] = $post_comment_report;
        $params[54] = $params[58] + $params[59] + $params[60];

        // Comment
        $params[77] = $personal_report;
        $params[78] = $post_report;
        $params[79] = $post_comment_report;
        $params[70] = $params[77] + $params[78] + $params[79];

        // Support
        $params[29] = DB::table('temp_chat')->where(['admin_id' => auth('admin')->id(), 'type' => 0, 'is_read' => 0])->count();
        $params[28] = $params[29];

        // Mail
        $params[72] = DB::table('mailbox')->where(['mailbox_status' => 0, 'object_type' => 1, 'is_deleted' => 0])->count();

        return $params;
    }
}
