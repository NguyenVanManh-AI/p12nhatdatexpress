<?php

namespace App\Observers;

use App\Enums\UserEnum;
use App\Models\Classified\ClassifiedReport;

class ClassifiedReportObserver
{
    /**
     * Handle the ClassifiedReport "created" event.
     *
     * @param  \App\Models\ClassifiedReport  $classifiedReport
     * @return void
     */
    public function created(ClassifiedReport $classifiedReport)
    {
        if ($classifiedReport->classified) {
            if ($classifiedReport->classified->reports()->active()->count() >= UserEnum::NUMBER_REPORT) {
                $classified = $classifiedReport->classified;
                $classified->is_show = 0;
                $classified->is_block = 1;
                $classified->save();
            }
        }

        if ($classifiedReport->classified_comment) {
            if ($classifiedReport->classified_comment->reports()->active()->count() >= UserEnum::NUMBER_REPORT) {
                $classified_comment = $classifiedReport->classified_comment;
                $classified_comment->is_show = 0;
                $classified_comment->save();
            }
        }
    }
}
