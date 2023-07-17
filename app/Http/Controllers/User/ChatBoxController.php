<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatBoxController extends Controller
{
    public function getUnreadMessages()
    {
        $user = Auth::guard('user')->user();

        return response()->json([
            'success' => true,
            'data' => [
                'unread' => $user->getUnreadMessages()
            ],
        ]);
    }
}
