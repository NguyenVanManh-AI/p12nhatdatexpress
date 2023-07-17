<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatBoxController extends Controller
{
    public function getUnreadMessages()
    {
        $admin = Auth::guard('admin')->user();

        return response()->json([
            'success' => true,
            'data' => [
                'unread' => $admin->getUnreadMessages()
            ],
        ]);
    }
}
