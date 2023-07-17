<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ChatStatus;
use App\Enums\ChatType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendMessageRequest;
use App\Http\Resources\ConversationMessageResource;
use App\Models\Conversation;
use App\Models\User;
use App\Models\User\UserType;
use App\Services\AdminService;
use App\Services\ConversationService;
use App\Services\UserService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    private AdminService $adminService;
    private ConversationService $conversationService;
    private UserService $userService;

    public function __construct()
    {
        $this->adminService = new AdminService;
        $this->conversationService = new ConversationService;
        $this->userService = new UserService;
    }

    public function index(Request $request)
    {
        $accountTypes = UserType::select('id', 'type_name')
            ->get();
        $ratings = [1, 2, 3, 4, 5];
        $chatTypes = ChatType::getValues();
        $statuses = ChatStatus::getValues();
        $careStaffs = $this->adminService->getSupportAccount();

        $admin = Auth::guard('admin')->user();
        if ($admin->admin_type != 1) {
            $request['admin_id'] = $admin->id;
        }
        $request['is_support'] = true;
        $conversations = $this->conversationService->index($request->all());

        return view('admin.supports.index', [
            'ratings' => $ratings,
            'accountTypes' => $accountTypes,
            'chatTypes' => $chatTypes,
            'careStaffs' => $careStaffs,
            'statuses' => $statuses,
            'conversations' => $conversations,
            'statisticsData' => [
                'all_count' => $conversations->count(),
                'pending_count' => $conversations->where('status', ChatStatus::PENDING)->count(),
                'chatting_count' => $conversations->where('status', ChatStatus::ACTIVE)->count(),
                'has_rating_count' =>$conversations->where('rating', '>', 0)->count()
            ]
        ]);
    }

    public function spam(Conversation $conversation)
    {
        // $this->conversationService->spam($conversation);
        if ($conversation->sender)
            $this->userService->spammedUser($conversation->sender);

        Toastr::success('Chặn Spam thành công.');
        return back();
    }

    public function unSpam(Conversation $conversation)
    {
        // $this->conversationService->spam($conversation);
        if ($conversation->sender)
            $this->userService->unSpammedUser($conversation->sender);

        Toastr::success('Bỏ chặn Spam thành công.');
        return back();
    }

    public function end(Conversation $conversation)
    {
        $this->conversationService->end($conversation);

        Toastr::success('Kết thúc thành công.');
        return back();
    }

    public function getMessages($token)
    {
        $user = Auth::guard('admin')->user();
        $conversation = $this->conversationService->getConversation($user, $token);

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hội thoại'
            ]);
        }

        $results = $this->conversationService->getMessages($conversation);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    public function openConversation(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $conversation = $this->conversationService->getConversation($user, $request->token);

        if (!$conversation) {
            return '';
        }


        $otherUser = $conversation->getOtherUserChat($user);

        session()->put('opened-conversation', [
            'token' => $conversation->token,
            'avatar' => $otherUser && $otherUser->getAvatarUrl() ? $otherUser->getAvatarUrl() : ''
        ]);

        // update read for only user message
        // maybe update read multiple user. use options->read_users json column
        $conversation->messages()
            // ->where('senderable_id', '!=', $user->id)
            ->where('senderable_type', User::class)
            ->where('read', false)
            ->update([
                'read' => true
            ]);

        $conversationMessages = $conversation->messages()
                    ->whereNull('options->admins_deleted->' . Auth::guard('admin')->user()->id)
                    ->latest('id')
                    ->paginate(20);

        return view('chats._popup-chat', [
            'actionUser' => $user,
            'conversation' => $conversation,
            'conversationMessages' => $conversationMessages,
        ]);
    }

    public function closeConversation()
    {
        session()->forget('opened-conversation');

        return response()->json([
            'success' => true,
        ]);
    }

    public function readConversation($token)
    {
        $user = Auth::guard('admin')->user();
        $conversation = $this->conversationService->getConversation($user, $token);
        $unreadMessages = $this->conversationService->readConversation($user, $conversation);

        return response()->json([
            'success' => true,
            'data' => [
                'unread_messages' => $unreadMessages,
                'last_read_id' => $conversation->getLastMessageOfUser($user)
                                    ? $conversation->getLastMessageOfUser($user)->id
                                    : null
            ]
        ]);
    }

    public function sendConversationMessage(SendMessageRequest $request)
    {
        $user = Auth::guard('admin')->user();
        $activeConversation = $this->conversationService->getConversation($user, $request->conversation_token);

        if (!$activeConversation) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hội thoại'
            ]);
        }

        // do not need check spam for admin
        if ($activeConversation->isEnded()) {
        // if ($activeConversation->isSpammed() || $activeConversation->isEnded()) {
            return response()->json([
                'success' => false,
                'message' => 'Cuộc hội thoại bị khóa'
            ]);
        }

        $message = $this->conversationService->sendMessage($user, $activeConversation, $request);

        return response()->json([
            'success' => true,
            'data' => [
                'message' => new ConversationMessageResource($message)
            ]
        ]);
    }
}
