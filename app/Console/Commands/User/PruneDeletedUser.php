<?php

namespace App\Console\Commands\User;

use App\Enums\UserEnum;
use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PruneDeletedUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:prune-deleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean deleted user by prune days. Default 30 days';

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
        $users = User::where('is_deleted', true)
            ->where('delete_time', '<=', time() - 60 * 60 * 24 * UserEnum::PRUNE_DAYS)
            ->get();

        foreach ($users as $user) {
            $userDirPath = Helper::get_upload_dir_user($user);

            // clean user image
            if (File::exists(public_path($userDirPath))) {
                File::deleteDirectory(public_path($userDirPath));
            }

            continue;
            // continue for now should add withIsDeleted() or withTrashed() for all realtionships

            // clean user classifieds, check \App\Observers\ClassifiedObserver
            $user->classifieds()
                ->forceDelete();

            $user->classifiedComments()
                ->forceDelete();

            $user->classifiedCommentLikes()
                ->detach();

            $user->classifiedReports()
                ->forceDelete();

            // clean user events, check \App\Observers\EventObserver
            $user->events()
                ->forceDelete();

            $user->projectComments()
                ->forceDelete();

            $user->projectCommentLikes()
                ->detach();

            $user->balances()
                ->forceDelete();

            $user->deposits()
                ->forceDelete();

            $user->coinRefReceipts()
                ->forceDelete();

            $user->transactions()
                ->forceDelete();

            $user->vouchers()
                ->forceDelete();

            $user->violates()
                ->forceDelete();

            $user->followers()
                ->forceDelete();

            $user->followings()
                ->forceDelete();

            $user->customers()
                ->forceDelete();

            $user->ratings()
                ->forceDelete();

            $user->ratingUsers()
                ->forceDelete();

            $user->ratingComments()
                ->forceDelete();
            
            $user->ratingCommentPersonals()
                ->forceDelete();

            $user->ratingLikes()
                ->forceDelete();

            $user->posts()
                ->forceDelete();

            $user->postComments()
                ->forceDelete();

            $user->postReports()
                ->forceDelete();

            $user->logContents()
                ->forceDelete();

            $user->detail()
                ->forceDelete();

            $user->location()
                ->forceDelete();

            $user->levelStatus()
                ->forceDelete();

            // clear dự án đang triển khai
            $user->projects()
                ->detach();

            $user->projectRequests()
                ->forceDelete();

            $user->forceDelete();

            $user->mailConfigs()
                ->withIsDeleted()
                ->forceDelete();

            $user->mailTemplates()
                ->withIsDeleted()
                ->forceDelete();

            $user->mailCampaigns()
                ->withIsDeleted()
                ->forceDelete();

            $user->notifies()
                ->withTrashed()
                ->forceDelete();
            // need delete customer_user_mail_campaign pivot & user_mail_campaign_detail
            // should recheck maybe missing st

            // $user->forceDelete();
        }

        return 0;
    }
}
