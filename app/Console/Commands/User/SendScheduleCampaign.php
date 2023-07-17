<?php

namespace App\Console\Commands\User;

use App\Enums\User\CampaignStatus;
use App\Jobs\User\SendCampaignDetailMail;
use App\Models\UserMailCampaign;
use App\Models\UserMailCampaignDetail;
use Illuminate\Console\Command;

class SendScheduleCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:send-schedule-campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto send schedule campaign mail for customer of user';

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
        if (!getEnabledMailCampaign()) return 0;

        // should check deleted mail template or when deleted mail template should delete mail campaign too
        // $scheduleCampaigns = UserMailCampaign::select('user_mail_campaign.*')
        $scheduleCampaignDetails = UserMailCampaignDetail::select('user_mail_campaign_detail.*')
            ->leftJoin('user_mail_campaign', 'user_mail_campaign.id', '=', 'user_mail_campaign_detail.campaign_id')
            ->where('user_mail_campaign.start_date', '<', now())
            ->where('user_mail_campaign.is_birthday', false)
            ->where('user_mail_campaign.status', CampaignStatus::PENDING)
            ->get();

        foreach ($scheduleCampaignDetails as $detail) {
            SendCampaignDetailMail::dispatch($detail->id);

            if ($detail->userMailCampaign && $detail->userMailCampaign->status == CampaignStatus::PENDING) {
                $detail->userMailCampaign->update([
                    'status' => CampaignStatus::SENDED
                ]);
            }

            sleep(30);
        }

        return 0;
    }
}
