<?php

namespace App\Observers;

use App\Models\Classified\Classified;

class ClassifiedObserver
{
    /**
     * Handle the Classified "force deleted" event.
     *
     * @param  \App\Models\Classified\Classified  $classified
     * @return void
     */
    public function forceDeleted(Classified $classified)
    {
        $classified->comments()->forceDeleted();
        $classified->location()->forceDeleted();
        $classified->ratings()->forceDeleted();
        $classified->reports()->forceDeleted();
    }
}
