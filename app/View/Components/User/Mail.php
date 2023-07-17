<?php

namespace App\View\Components\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Mail extends Component
{
    public $mails;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $user = Auth::guard('user')->user();
        $this->mails = DB::table('mailbox')
            ->select('mail_title', 'send_time', 'id')
            ->where('mailbox_status', 0)
            ->where('user_id', $user->id)
            ->where('is_deleted', '<>', 1)
            ->orderBy('send_time', 'desc')
            ->take(10)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user.mail');
    }
}
