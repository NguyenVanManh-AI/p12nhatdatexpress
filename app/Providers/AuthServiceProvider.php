<?php

namespace App\Providers;

use App\Models\Classified\Classified;
use App\Models\Classified\ClassifiedComment;
use App\Models\Event\Event;
use App\Models\MailBox;
use App\Models\Project;
use App\Models\ProjectComment;
use App\Policies\Classified\ClassifiedCommentPolicy;
use App\Policies\ClassifiedPolicy;
use App\Policies\EventPolicy;
use App\Policies\MailBoxPolicy;
use App\Policies\Project\ProjectCommentPolicy;
use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Classified::class => ClassifiedPolicy::class,
        ClassifiedComment::class => ClassifiedCommentPolicy::class,
        Event::class => EventPolicy::class,
        MailBox::class => MailBoxPolicy::class,
        Project::class => ProjectPolicy::class,
        ProjectComment::class => ProjectCommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
