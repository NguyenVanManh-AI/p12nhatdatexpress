<?php

namespace App\Observers;

use App\Models\Event\Event;

class EventObserver
{
    /**
     * Handle the Event "force deleted" event.
     *
     * @param  \App\Models\Event\Event  $event
     * @return void
     */
    public function forceDeleted(Event $event)
    {
        $event->location()->forceDelete();
        $event->report()->forceDelete();
        $event->ratings()->forceDelete();
    }
}
