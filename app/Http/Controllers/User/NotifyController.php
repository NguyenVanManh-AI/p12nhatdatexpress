<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notify;
use Illuminate\Http\Request;

class NotifyController extends Controller
{
    public function read(Notify $notify)
    {
        if (!$notify->read) {
            $notify->update([
                'read' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ẩn thành công'
        ], 200);
    }
}
