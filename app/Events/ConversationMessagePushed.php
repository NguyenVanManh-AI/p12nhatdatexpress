<?php

namespace App\Events;

use App\Http\Resources\ConversationMessageResource;
use App\Models\ConversationMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationMessagePushed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ConversationMessage $conversationMessage;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ConversationMessage $conversationMessage)
    {
        $this->conversationMessage = $conversationMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('conversation.' . $this->conversationMessage->conversation->token);
    }

    public function broadcastWith()
    {
        return [
            'message' => (new ConversationMessageResource($this->conversationMessage))->resolve([]),
        ];
    }
}
