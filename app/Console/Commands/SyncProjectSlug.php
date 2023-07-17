<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SyncProjectSlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:project-slug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync project slug';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Project::get()
            ->each(function ($project) {
                $project->update([
                    'project_url' => Str::slug($project->project_name)
                ]);
            });

        return 0;
    }
}
