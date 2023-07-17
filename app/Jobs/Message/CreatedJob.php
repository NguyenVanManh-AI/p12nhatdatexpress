<?php

namespace App\Jobs\Message;

use App\Events\UserConversationPushed;
use App\Models\ConversationMessage;
use App\Models\ForbiddenWord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ConversationMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $messageContent = $this->message->message;
        $conversation = $this->message->conversation;

        $hasBanWords = false;

        $blockedWords = ForbiddenWord::select('forbidden_word')
            ->pluck('forbidden_word');

        // $baseContent = strtolower($messageContent);
        foreach($blockedWords as $word) {
            $word = strtolower($word);
            // remove special characters keep vietnamese
            $word = preg_replace('/[!#$%^&*()+=\-_\[\]\';,.\/{}|":<>@?~\\\\¿§«»ω⊙¤°℃℉€¥£¢¡®©]/', '', $word);
            $messageContent = preg_replace("/\b$word\b/i", '***', $messageContent, -1, $count);
            // {$word}
            if (!$hasBanWords) {
                $hasBanWords = $count > 0;
            }
        }

        // foreach ($conversation->users()->get() as $userChat) {
        //     broadcast(new UserConversationPushed($userChat, $this->message));
        // }
    }
}
