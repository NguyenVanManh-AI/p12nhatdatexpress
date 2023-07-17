<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminConfig;
use App\Models\User\UserLevel;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    public function index()
    {
        $user = Auth::guard('user')->user();
        $userLevels = UserLevel::showed()
            ->oldest('id')
            ->get();
        $totalClassifieds = $user->classifieds->count();
        $guide = AdminConfig::firstWhere('config_code', 'N013');

        return view('user.level.index', [
            'user' => $user,
            'userLevels' => $userLevels,
            'totalClassifieds' => $totalClassifieds,
            'guide' => $guide
        ]);
    }
}
