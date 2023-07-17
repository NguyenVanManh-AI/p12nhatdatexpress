<?php

namespace App\Traits\Models;

use App\Models\ConversationMessage;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait UserHelper
{
    public function getChatName()
    {
        return $this->getFullName();
    }

    public function getChannelTokenAttribute($value)
    {
        $token = $value;

        if (!$value) {
            $token = md5($this->id . uniqid() . date('d-m-Y H:i:s'));
            $this->channel_token = $token;
            $this->save();
        }

        return $token;
    }

    public function isSupport(): bool
    {
        return false;
    }

    /**
     * chat box
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class);
    }

    public function conversationMessages(): MorphMany
    {
        return $this->morphMany(ConversationMessage::class, 'senderable');
    }

    public function getConversationToken($id)
    {
        $conversation = $this->conversations()->where(function($query) use ($id) {
                return $query->orWhere('sender_id', $id)
                    ->orWhere('receiver_id', $id);
            })
            ->first();

        return $conversation ? $conversation->token : md5($this->id . $id . uniqid());
    }

    // public function getUnreadMessages()
    // {
    //     $messages = ConversationMessage::selectRaw('conversation_messages.*')
    //         ->where('conversation_messages.sender_id', '!=', $this->id)
    //         ->join('conversations as conversations', 'conversations.id', '=', 'conversation_messages.conversation_id')
    //         ->join('conversation_user as conversation_user', 'conversation_user.conversation_id', '=', 'conversations.id')
    //         ->join('users as users', 'users.id', '=', 'conversation_messages.sender_id')
    //         ->where('conversation_user.user_id', $this->id)
    //         ->where('read', false)
    //         ->count();

    //     return $messages;
    // }

    public function getUnreadMessages()
    {
        $messages = ConversationMessage::selectRaw('conversation_messages.*')
                        ->where(function ($query) {
                            return $query->where('conversation_messages.senderable_id', '!=', $this->id)
                                ->orWhere('conversation_messages.senderable_type', '!=', self::class);
                        })
                        ->join('conversations', 'conversations.id', '=', 'conversation_messages.conversation_id')
                        ->join('conversation_user', 'conversation_user.conversation_id', '=', 'conversations.id')
                        // ->join('user', 'user.id', '=', 'conversation_messages.sender_id')
                        // ->whereNull('user.deleted_at')
                        ->where('conversation_user.user_id', $this->id)
                        ->where('read', false)
                        ->count();

        return $messages;
    }
}
