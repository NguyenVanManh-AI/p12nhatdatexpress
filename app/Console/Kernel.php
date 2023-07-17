<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\ClassifiedViewOfWeek::class,

    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        #Xoa user chua active hoat confirm sau 15 ngay
         $schedule->command('delete:not-confirm-account')->dailyAt('00:00');
//        $schedule->command('EmailCampaignSetTime:cron')->everyMinute();
//        $schedule->command('EmailCampaignBirthday:cron')->dailyAt("09:00");
        $schedule->command('classifiedview:week')->weeklyOn(0, '00:00');
        $schedule->command('admin_mail_campaign:cron')->everyMinute();
        #user reset view of day
         $schedule->command('user:reset-view-of-day')->dailyAt('00:00');

        $schedule->command('classified:prune')->dailyAt('00:00');
        $schedule->command('user:reset-default-package-amount')->monthly();

        // clean all deleted users after 30 days
        $schedule->command('user:prune-deleted')->dailyAt('00:00');

        // user mail campaign
        $schedule->command('user:send-schedule-campaign')->everyMinute();
        $schedule->command('user:send-birthday-campaign')->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
