<?php

namespace App\Observers;

use App\Enums\UserEnum;
use App\Models\Event\EventReport;

class EventReportObserver
{
    /**
     * Handle the EventReport "created" event.
     *
     * @param  \App\Models\Event\EventReport  $eventReport
     * @return void
     */
    public function created(EventReport $eventReport)
    {
        if ($eventReport->event) {
            if ($eventReport->event->reports()->active()->count() >= UserEnum::NUMBER_REPORT) {
                $event = $eventReport->event;
                $event->is_block = 1;
                $event->save();
            }
        }
    }
}
