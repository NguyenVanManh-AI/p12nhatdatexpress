<?php

namespace App\View\Components\User\Index;

use App\Models\User\UserDetail;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SocialLink extends Component
{
    public $user_social_link;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $user = Auth::guard('user')->user();
        $this->user_social_link = UserDetail::select('facebook', 'youtube', 'twitter')
            ->where('user_id', $user->id)
            ->first();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user.index.social-link');
    }
}
