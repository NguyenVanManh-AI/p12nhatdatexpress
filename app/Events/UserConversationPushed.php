<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserConversationPushed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user; // user or admin
    // public ConversationMessage $conversationMessage;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        // $this->conversationMessage = $conversationMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user->channel_token);
    }

    // public function broadcastWith()
    // {
    //     $this->conversationMessage->total_unread = $this->conversationMessage->conversation->getUnreadMessage($this->user);

    //     return [
    //         'message' => (new ConversationMessageResource($this->conversationMessage))->resolve([]),
    //     ];
    // }
}
