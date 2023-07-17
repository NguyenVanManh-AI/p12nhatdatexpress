<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            // 'message' => htmlspecialchars($this->message),
            'message' => $this->message,
            'conversation_token' => data_get($this->conversation, 'token'),
            'sender' => $this->when($this->senderable, function() {
                return [
                    'avatar' => $this->senderable->getAvatarUrl(),
                    'name' => $this->senderable->getChatName(),
                ];
            }),
            'sender_channel_token' => data_get($this->senderable, 'channel_token'),
            'unique_id' => $this->when($this->unique_id, $this->unique_id),
            'sender_id' => $this->senderable_id,
            'type' => $this->type,
            'time' => $this->created_at ? Carbon::parse($this->created_at)->format('d/m/Y H:i') : null,
            // 'total_unread' => $this->when($this->total_unread, $this->total_unread),
        ];
    }
}
