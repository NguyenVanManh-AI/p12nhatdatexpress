<?php

namespace App\Traits\Models;

use App\Enums\HistoryAction;
use App\Jobs\CreateHistoryJob;
use Illuminate\Support\Facades\Auth;

trait UserHistoryTrait
{
    public static function bootUserHistoryTrait()
    {
        if (!Auth::guard('user')->user()->id) return;
        $data['action_user_id'] = Auth::guard('user')->user()->id;
    }
}
