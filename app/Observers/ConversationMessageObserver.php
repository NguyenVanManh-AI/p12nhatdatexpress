<?php

namespace App\Observers;

use App\Events\ConversationMessagePushed;
use App\Events\UserConversationPushed;
use App\Models\ConversationMessage;
use App\Services\AdminService;

class ConversationMessageObserver
{
    /**
     * Handle the ConversationMessage "created" event.
     *
     * @param  \App\Models\ConversationMessage  $conversationMessage
     * @return void
     */
    public function created(ConversationMessage $conversationMessage)
    {
        // $conversationMessage->unique_id = request()->unique_id;

        $conversation = $conversationMessage->conversation;

        if ($conversation) {
            $conversationMessage->unique_id = request()->unique_id;

            broadcast(new ConversationMessagePushed($conversationMessage));

            foreach ($conversation->users()->get() as $userChat) {
                broadcast(new UserConversationPushed($userChat));
            }

            if ($conversation->isSupport()) {
                $careStaffs = (new AdminService)->getSupportAccount(true);
                foreach ($careStaffs as $careStaff) {
                    broadcast(new UserConversationPushed($careStaff));
                }
            }
        }
    }
}
