<?php

namespace App\Http\Controllers\Admin\MailCampaign;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiMailCampaignController extends Controller
{
    public function get_users(): JsonResponse
    {
        $items = 50;
        $data = User::select('id', 'email', 'username')->where('is_deleted', 0)->paginate($items);
        return response()->json($data);
    }
}
