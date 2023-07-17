<?php

namespace App\Services;

use App\Enums\ChatStatus;
use App\Enums\ConversationMessageType;
use App\Http\Resources\ConversationMessageResource;
use App\Jobs\Message\CreatedJob;
use App\Models\Admin;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;

class ConversationService
{
    /**
     * Get all conversations
     * @param array $queries
     *
     * @return Collection|null $users
     */
    public function index(array $queries = [])
    {
        $itemsPerPage = data_get($queries, 'items') ?: 10;
        $page = data_get($queries, 'page') ?: 1;

        $isSupport = data_get($queries, 'is_support');
        $senderId = data_get($queries, 'sender_id');
        $adminId = data_get($queries, 'admin_id');

        $conversations = Conversation::select('conversations.*')
            ->when($isSupport, function ($query) {
                return $query->support();
            })
            ->when($senderId, function ($query, $senderId) {
                return $query->where('sender_id', $senderId);
            })
            ->when($adminId, function ($query, $adminId) {
                return $query->where('admin_id', $adminId)
                    ->orWhereNull('receiver_id');
            });
            // ->when(data_get($queries, 'keyword'), function ($query, $keyWord) {
            //     $query->where(function ($query) use ($keyWord) {
            //         return $query->where('user_detail.fullname', 'LIKE', '%' . $keyWord . '%')
            //             ->orWhere('user.email', 'LIKE', '%' . $keyWord . '%')
            //             ->orWhere('user.phone_number', 'LIKE', '%' . $keyWord . '%');
            //     });
            // })
        // should add filter

        $conversations = $conversations->latest()
            // ->groupBy('conversations.id')
            ->distinct()
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $conversations;
    }

    /**
     * get conversation
     * @param $user admin or user
     * @param string $token
     * @param int $receiverId = null
     * @param bool $isSupport = true
     * 
     * @return Conversation $conversation
     */
    public function getConversation($user, $token, $receiverId = null, $isSupport = true)
    {
        $senderId = null;
        if (get_class($user) == User::class) {
            $senderId = $user->id;
        } elseif (get_class($user) == Admin::class) {
            $isSupport = true;
        }

        if (!$token) return null;

        $conversation = Conversation::where('token', $token)
            ->when($receiverId, function ($query, $receiverId) {
                return $query->where('receiver_id', $receiverId);
            })
            ->when($senderId, function ($query, $senderId) {
                return $query->where('sender_id', $senderId);
            })
            // ->active()
            ->support($isSupport)
            ->first();

        return $conversation;
    }

     /**
     * get conversation from receiver id
     * @param $user admin or user
     * @param int $receiverId
     * @param bool $isSupport = true
     * 
     * @return Conversation $conversation
     */
    public function getConversationFromReceiver($user, $receiverId, $isSupport = true)
    {
        $senderId = null;
        if (get_class($user) == User::class) {
            $senderId = $user->id;
        } elseif (get_class($user) == Admin::class) {
            $isSupport = true;
        }

        if (!$receiverId) return null;

        $receiverColumn = $isSupport ? 'conversations.admin_id' : 'conversations.receiver_id';

        $conversation = Conversation::query()
            ->where('sender_id', $senderId)
            ->support($isSupport)
            ->when($isSupport, function ($query) {
                return $query->whereNotNull('receiver_id');
            })
            ->where(function ($query) use ($receiverColumn, $receiverId) {
                return $query->where($receiverColumn, $receiverId);
            })
            ->active()
            ->first();

        return $conversation;
    }

    /**
     * Get messages
     * @param Conversation $conversation
     * 
     * @return Array $messages
     */
    public function getMessages(Conversation $conversation)
    {
        $messages = $conversation->messages()
                        ->with(['senderable', 'conversation'])
                        ->where(function($query) {
                            return $query->whereJsonDoesntContain('options', ['removed' => true])
                                    ->orWhereNull('options');
                        })
                        ->latest('id')
                        ->paginate(20);

        return [
            'messages' => ConversationMessageResource::collection($messages),
            'meta' => [
                'total' => $messages->total(),
            ]
        ];
    }

    /**
     * Create a new Conversation
     * @param int $senderId
     * @param int|null $receiverId id of receiver user or support, can be null
     * @param array $data
     * 
     * @return Conversation $conversation
     */
    public function create($senderId, $receiverId = null, $data)
    {
        $isSupport = data_get($data, 'is_support') ? true : false;
        $adminId = $isSupport ? $receiverId : null;

        $conversation = Conversation::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId, // admin|user
            'admin_id' => $adminId,
            'is_support' => $isSupport,
            'type' => data_get($data,'type'),
            'status' => data_get($data, 'status', ChatStatus::PENDING),
            'token' => md5(uniqid($senderId) . date('d-m-Y H:i:s'))
        ]);

        $receiverUserId = $isSupport ? null : $receiverId;
        $userIds = [$senderId, $receiverUserId];

        $conversation->users()->sync(array_filter($userIds));

        return $conversation;
    }

    /**
     * block spam conversation
     * @param Conversation $conversation
     * 
     * @return void
     */
    // public function spam(Conversation $conversation)
    // {
    //     $conversation->update([
    //         'spammed_at' => now()
    //     ]);
    // }

    /**
     * block spam conversation
     * @param Conversation $conversation
     * 
     * @return void
     */
    public function end(Conversation $conversation)
    {
        if ($conversation->isEnded()) return;

        $conversation->update([
            'status' => ChatStatus::ENDED
        ]);
    }

    /**
     * block spam conversation
     * @param Conversation $conversation
     * @param int $rating 1-5 stars
     * 
     * @return void
     */
    public function rating(Conversation $conversation, int $rating)
    {
        if ($conversation->isSpammed()) return;

        $conversation->update([
            'rating' => $rating
        ]);
    }

    /**
     * Create conversation message
     * @param $user admin|user
     * @param Conversation $conversation
     * @param array $data
     * 
     * @return ConversationMessage $message
     */
    public function sendMessage($user, Conversation $conversation, Request $request)
    {
        // maybe check block & spam here
        $messageContent = $request->message;
        // Upload file
        $type = null;
        if ($request->hasFile('attach')) {
            $type = ConversationMessageType::ATTACH;
            // $messageContent = Storage::disk('local')->putFile('chat-attachs', $request->file('attach'));
        }

        $message = $user->conversationMessages()->create([
            'conversation_id' => $conversation->id,
            'message' => $messageContent,
            'type' => $type,
        ]);

        // update first reply admin
        if (get_class($user) == Admin::class && !$conversation->receiver_id && !$conversation->admin_id) {
            $conversation->update([
                'admin_id' => $user->id,
                'status' => ChatStatus::ACTIVE,
            ]);
        }

        CreatedJob::dispatch($message);

        return $message;
    }

    /**
     * Update read all message for user
     * @param $user admin|user
     * @param Conversation $conversation
     * 
     * @return $userUnreadMessagesCount
     */
    public function readConversation($user, Conversation $conversation)
    {
        $isSupporter = get_class($user) == Admin::class;

        $conversation->messages()
            ->where(function ($query) use ($user, $isSupporter) {
                if ($isSupporter) {
                    return $query->where('senderable_type', '!=', get_class($user));
                } else {
                    // should check
                    return $query->where('senderable_id', '!=', $user->id)
                        ->orWhere('senderable_type', '!=', get_class($user));
                }
            })
            ->where('read', false)
            ->update([
                'read' => true
            ]);

        return $user->getUnreadMessages();
    }
}

