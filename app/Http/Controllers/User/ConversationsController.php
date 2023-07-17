<?php

namespace App\Http\Controllers\User;

use App\Enums\ChatStatus;
use App\Enums\ChatType;
use App\Enums\ConversationEnum;
use App\Enums\ConversationMessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMessageRequest;
use App\Http\Requests\User\Conversations\CreateBasicChatRequest;
use App\Http\Requests\User\Conversations\CreateConversationRequest;
use App\Http\Requests\User\Conversations\NewConversationRequest;
use App\Http\Requests\User\Conversations\RatingRequest;
use App\Http\Requests\User\SendMessageRequest;
use App\Http\Resources\ConversationMessageResource;
use App\Http\Resources\ConversationResource;
use App\Jobs\Message\CreatedJob;
use App\Models\Admin;
use App\Models\Conversation;
use App\Models\User;
use App\Services\ConversationService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ConversationsController extends Controller
{
    private ConversationService $conversationService;

    public function __construct()
    {
        $this->conversationService = new ConversationService;
    }

    public function index(Request $request)
    {
        $conversations = $this->conversationService->all(Auth::user(), $request->all());

        return $conversations;
    }

    public function searchUser(SearchUserRequest $request)
    {
        $users = $this->conversationService->searchUser(Auth::user(), $request->all());

        return response()->json([
            'success' => true,
            'data' => [
                'users' => $users ? ConversationUserListResource::collection($users) : new \stdClass,
            ]
        ]);
    }

    /**
     * Get total unread messages for user
     * @return $userUnreadMessages
     */
    public function getUnreadMessages()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'unread_messages' => Auth::user()->getUnreadMessages()
            ]
        ]);
    }

    public function getConversation($token)
    {
        $conversation = $this->conversationService->getConversation(Auth::guard('user')->user(), $token);
        $this->authorize('show', $conversation);

        return response()->json([
            'success' => true,
            'data' => [
                'conversation' => (new ConversationResource($conversation))->resolve([]),
            ]
        ]);
    }

    public function newConversation(Request $request)
    {
        $user = Auth::guard('user')->user();

        $isSupport = $request->is_support ? true : false;
        $receiverChatId = $request->user_chat_id;

        if (!$receiverChatId)
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy người nhận'
            ], 401);

        // $token = $request->token;
        // if ($token) {
        //     $activeConversation = $user->conversations()
        //         ->active()
        //         ->firstWhere('token', $token);
        // } elseif ($receiverChatId) {

        $activeConversation = $this->conversationService->getConversationFromReceiver($user, $receiverChatId, $isSupport);

        // $activeConversation = $user->conversations()
        //     ->active()
        //     ->where(function ($query) use ($isSupport, $user, $receiverChatId) {
        //         if ($isSupport) {
        //             // support
        //             $query = $query->where('admin_id', $receiverChatId);
        //         } else {
        //             // user
        //             $query = $query->where(function($query) use ($user, $receiverChatId) {
        //                 return $query->where([
        //                     'sender_id' => $user->id,
        //                     'receiver_id' => $receiverChatId
        //                 ]);
        //             });
        //         }

        //         return $query->support($isSupport);
        //     })
        //     ->first();

        if (!$activeConversation) {
            $conversation = new Conversation([
                'sender_id' => $user->id,
                'receiver_id' => null,
                'admin_id' => $receiverChatId,
                'is_support' => 1,
                'status' => ChatStatus::PENDING,
                // 'token' => md5(uniqid($user->id) . date('d-m-Y H:i:s'))
            ]);

            $chatTypes = ChatType::getValues();

            session()->forget('opened-conversation');

            $html = view('chats._popup-chat', [
                'actionUser' => $user,
                'conversation' => $conversation,
                'chatTypes' => $chatTypes,
                'conversationMessages' => new Collection(),
            ])->render();

            return response()->json([
                'success' => true,
                'data' => [
                    'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
                ]
            ]);
        }

        // if (!$activeConversation) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => __('Not found conversation.')
        //     ]);
        // }

        return response()->json([
            'success' => true,
            'data' => [
                'conversation' => (new ConversationResource($activeConversation))->resolve([]),
            ]
        ]);
    }

    public function getMessages($token)
    {
        $user = Auth::guard('user')->user();
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

    // public function getLastMessage($token)
    // {
    //     $conversation = $this->conversationService->getConversation(Auth::user(), $token);
    //     $this->authorize('list-messages', $conversation);

    //     $lastMessage = $conversation->getLastMessageOfUser();

    //     return response()->json([
    //         'success' => true,
    //         'data' => [
    //             // 'message' => $lastMessage ? (new ConversationLastMessageResource($lastMessage))->resolve([]) : new \stdClass,
    //         ]
    //     ]);
    // }

    public function sendMessage(CreateMessageRequest $request, $token)
    {
        $user = Auth::user();
        $conversation = $this->conversationService->getConversation($user, $token);
        $this->authorize('create-message', $conversation);

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'message' => _('Something was wrong')
            ], 403);
        }

        $messageContent = $request->message;

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'message' => $messageContent,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'message' => (new ConversationMessageResource($message))->resolve([]),
            ]
        ]);
    }

    public function sendBasicChat(CreateBasicChatRequest $request)
    {
        $user = Auth::guard('user')->user();

        $isSupport = $request->is_support ? true : false;

        // should check limit request per day
        $currentDayRequests = $user->conversations()
            ->support($isSupport)
            ->whereNull('admin_id') // maybe add conversation type = 'Request' or save to options. change type to request type
            ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
            ->count();

        if ($currentDayRequests >= ConversationEnum::LIMIT_REQUEST_PER_DAY) {
            Toastr::error('Chỉ được gửi tối đa ' . ConversationEnum::LIMIT_REQUEST_PER_DAY . ' yêu cầu / ngày');
            return back()->withInput();
        }

        $data = [
            'is_support' => $isSupport,
            'type' => $request->chat_type,
        ];

        $activeConversation = $this->conversationService->create(
            $user->id,
            null,
            $data
        );

        $this->conversationService->sendMessage($user, $activeConversation, $request);

        Toastr::success('Gửi thông tin thành công');
        return back();
    }

    public function sendConversationMessage(SendMessageRequest $request)
    {
        $user = Auth::guard('user')->user();
        $token = $request->conversation_token;
        $receiverChatId = $request->user_chat_id;
        $isSupport = $request->is_support ? true : false;
        $activeConversation = $this->conversationService->getConversation($user, $token);

        if (!$activeConversation && $receiverChatId) {
            // create new
            $activeConversation = $this->conversationService->getConversationFromReceiver($user, $receiverChatId, $isSupport);

            if (!$activeConversation) {
                $chatType = $request->chat_type;

                if (!$chatType) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vui lòng chọn mục cần hỗ trợ'
                    ], 201);
                }

                // userChatId - check admin is supporter || receiver can chat
                $data = [
                    'is_support' => $isSupport,
                    'type' => $chatType,
                    'status' => ChatStatus::ACTIVE,
                ];

                $activeConversation = $this->conversationService->create(
                    $user->id,
                    $receiverChatId,
                    $data
                );

                $otherUser = $activeConversation->getOtherUserChat($user);
                session()->put('opened-conversation', [
                    'token' => $activeConversation->token,
                    'avatar' => $otherUser && $otherUser->getAvatarUrl() ? $otherUser->getAvatarUrl() : ''
                ]);
            }
        }

        if (!$activeConversation) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hội thoại'
            ]);
        }

        if ($activeConversation->isSpammed() || $activeConversation->isEnded()) {
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

    public function readConversation($token)
    {
        $user = Auth::guard('user')->user();
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

    // public function addAttach(AttachRequest $request, $token)
    // {
    //     $user = Auth::user();
    //     $conversation = $this->conversationService->getConversation($user, $token);
    //     $this->authorize('create-message', $conversation);

    //     list($success, $message) = $this->conversationService->addAttach($user, $conversation, $request->attach);

    //     // Can't create message
    //     if (!$success) {
    //         return response()->json([
    //             'success' => false,
    //             'data' => [
    //                 'unique_id' => $request->unique_id,
    //             ],
    //         ], 422);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => [
    //             'message' => new ConversationMessageResource($message)
    //         ]
    //     ]);
    // }

    public function removeMessage(ConversationMessage $message)
    {
        $this->authorize('remove', $message);

        $options = $message->options ?: [];
        $options['removed'] = true;

        if ($message->type === ConversationMessageType::ATTACH && Storage::disk('public')->exists($message->message)) {
            Storage::disk('public')->delete($message->message);
        }

        $message->update([
            'options' => $options,
            'read' => 1
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function openConversation(Request $request)
    {
        $user = Auth::guard('user')->user();
        // $token = $request->token;

        $conversation = $this->conversationService->getConversation($user, $request->token);

        if (!$conversation) {
            return '';
        }

        $otherUser = $conversation->getOtherUserChat($user);

        session()->put('opened-conversation', [
            'token' => $conversation->token,
            'avatar' => $otherUser && $otherUser->getAvatarUrl() ? $otherUser->getAvatarUrl() : ''
        ]);

        // $openConversations = session('conversations', []);

        // if (!isset($openConversations[$request->token])) {
        //     if (count($openConversations) > 3) {
        //         $openConversations = array_slice($openConversations, -3, 3, true);
        //     }

        //     $openConversations[$request->token] = true;

        //     session()->put('conversations', $openConversations);
        // }

        $conversation->messages()
            ->where(function ($query) use ($user) {
                return $query->where('senderable_id', '!=', $user->id)
                    ->orWhere('senderable_type', '!=', User::class);
            })
            ->where('read', false)
            ->update([
                'read' => true
            ]);

        $conversationMessages = $conversation->messages()
                    ->whereNull('options->users_deleted->' . Auth::guard('user')->user()->id)
                    ->orderBy('id', 'DESC')
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

    public function rating($token, RatingRequest $request)
    {
        $user = Auth::guard('user')->user();
        $activeConversation = $this->conversationService->getConversation($user, $token);

        if (!$activeConversation) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hội thoại'
            ]);
        }

        if ($activeConversation->isSpammed() || $activeConversation->isEnded()) {
            return response()->json([
                'success' => false,
                'message' => 'Cuộc hội thoại bị khóa'
            ]);
        }

        $message = $this->conversationService->rating($activeConversation, $request->rating);

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá thành công'
        ]);
    }
}
