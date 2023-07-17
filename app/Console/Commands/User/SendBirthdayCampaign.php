<?php

namespace App\Console\Commands\User;

use App\Enums\User\CampaignStatus;
use App\Jobs\User\SendCampaignDetailMail;
use App\Models\UserMailCampaignDetail;
use Illuminate\Console\Command;

class SendBirthdayCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:send-birthday-campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto send birthday campaign mail for customer of user';

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
        $startTime = now()->startOfDay()->timestamp;
        $endTime = now()->endOfDay()->timestamp;

        $birthdayCampaignDetails = UserMailCampaignDetail::select('user_mail_campaign_detail.*')
            ->leftJoin('customer', 'customer.id', '=', 'user_mail_campaign_detail.cus_id')
            ->leftJoin('user_mail_campaign', 'user_mail_campaign.id', '=', 'user_mail_campaign_detail.campaign_id')
            ->whereBetween('customer.birthday', [$startTime, $endTime])
            ->whereNull('user_mail_campaign.start_date')
            ->where('user_mail_campaign.is_birthday', true)
            ->where('user_mail_campaign.status', '!=', CampaignStatus::CANCELLED)
            ->get();

        foreach ($birthdayCampaignDetails as $detail) {
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
