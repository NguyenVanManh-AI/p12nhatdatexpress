<?php

namespace App\View\Components\Home;

use App\Models\ReportGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ReportModal extends Component
{
    public $report_content;
    public $report_comment;
    public $report_persolnal;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        // // báo cáo bài viết
        // $report_content = DB::table('report_group')->where([
        //     'type'=>1,
        //     'is_show'=>1,
        // ])->select('id','content')->get();
        // // báo cáo bình luận
        // $report_comment = DB::table('report_group')->where([
        //     'type'=>2,
        //     'is_show'=>1,
        // ])->select('id','content')->get();
        // // báo cáo trang cá nhân
        // $report_persolnal = DB::table('report_group')->where([
        //     'type'=>3,
        //     'is_show'=>1,
        // ])->select('id','content')->get();

        $reports = ReportGroup::select('id','content', 'type')
            ->showed()
            ->whereIn('type', [1, 2, 3])
            ->get();

        $this->report_content = $reports->where('type', 1);
        $this->report_comment = $reports->where('type', 2);
        $this->report_persolnal = $reports->where('type', 3);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.report-modal');
    }
}
