<?php

namespace App\Listeners;
use Illuminate\Support\Facades\DB;
use App\Events\BlockKeyword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckKeywordBlock
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\BlockKeyword  $event
     * @return void
     */
    public function handle(BlockKeyword $event)
    {
        $block = DB::table('forbidden_word')->where('is_deleted',0)->select('forbidden_word')->get();
        foreach($block as $item){
            if(str_contains($event->keyword,mb_strtolower(str_replace(" ","",$item->forbidden_word))) == true)
            {
               return $item->forbidden_word;    
            }
        }
        
        return true;
    }
}
