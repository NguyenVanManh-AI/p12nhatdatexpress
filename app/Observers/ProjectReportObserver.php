<?php

namespace App\Observers;

use App\Enums\UserEnum;
use App\Models\ProjectReport;

class ProjectReportObserver
{
    /**
     * Handle the ProjectReport "created" event.
     *
     * @param  \App\Models\ProjectReport  $projectReport
     * @return void
     */
    public function created(ProjectReport $projectReport)
    {
        if ($projectReport->project) {
            if ($projectReport->project->reports()->active()->count() >= UserEnum::NUMBER_REPORT) {
                $project = $projectReport->project;
                $project->is_show = 0;
                $project->save();
            }
        }

        if ($projectReport->project_comment) {
            if ($projectReport->project_comment->reports()->active()->count() >= UserEnum::NUMBER_REPORT) {
                $project_comment = $projectReport->project_comment;
                $project_comment->is_show = 0;
                $project_comment->save();
            }
        }
    }
}
