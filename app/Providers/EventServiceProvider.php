<?php

namespace App\Providers;

use App\Events\Admin\Auth\SendLinkReset;
use App\Listeners\SendLinkResetLister;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\BlockKeyword;
use App\Listeners\CheckKeywordBlock;
use App\Models\Classified\Classified;
use App\Models\Classified\ClassifiedReport;
use App\Models\ConversationMessage;
use App\Models\Event\Event as EventEvent;
use App\Models\Event\EventReport;
use App\Models\ProjectReport;
use App\Models\User\UserPostReport;
use App\Observers\ClassifiedObserver;
use App\Observers\ClassifiedReportObserver;
use App\Observers\ConversationMessageObserver;
use App\Observers\EventObserver;
use App\Observers\EventReportObserver;
use App\Observers\ProjectReportObserver;
use App\Observers\UserPostReportObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        BlockKeyword::class=>[
            CheckKeywordBlock::class
        ],
        SendLinkReset::class=>[
            SendLinkResetLister::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        ClassifiedReport::observe(ClassifiedReportObserver::class);
        EventReport::observe(EventReportObserver::class);
        ProjectReport::observe(ProjectReportObserver::class);
        UserPostReport::observe(UserPostReportObserver::class);
        EventEvent::observe(EventObserver::class);
        ConversationMessage::observe(ConversationMessageObserver::class);
        Classified::observe(ClassifiedObserver::class);
    }
}
