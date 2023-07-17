<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        // $lastMessage = $this->getLastMessageOfUser();

        $otherUser = $this->getOtherUserChat(Auth::user()->id);

        if (!$otherUser && $this->system) {
            $otherUser = [
                'is_online' => true,
                'name' => __('common.system'),
                'name_url' => '/img/logo-only-vertical-blue',
            ];
        }

        return [
            'id' => $this->id,
            'token' => $this->token,
            // 'last_message' => $lastMessage ? (new ConversationLastMessageResource($lastMessage))->resolve([]) : new \stdClass,
            // 'unread' => $this->getUnreadMessage(Auth::user()->id),
            'user' => [
                'id' => data_get($otherUser, 'id'),
                'is_online' => data_get($otherUser, 'is_online') ? true : false,
                'name' => data_get($otherUser, 'name'),
                'name_url' => data_get($otherUser, 'name_url'),
            ],
            'system' => $this->system,
        ];
    }
}
