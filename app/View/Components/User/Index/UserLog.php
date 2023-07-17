<?php

namespace App\View\Components\User\Index;

use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserLog extends Component
{

    public $user_logs;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $user = Auth::guard('user')->user();
        $this->user_logs = DB::table('user_log_content as ulc')
            ->select('ulc.log_time', 'ul.log_content', 'ul.log_icon', 'ulc.log_message')
            ->where('ulc.user_id', $user->id)
            ->join('user_log as ul', 'ulc.log_id', '=', 'ul.id')
            ->orderByDesc('ulc.log_time')
            ->take(5)
            ->get();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user.index.user-log');
    }
}
