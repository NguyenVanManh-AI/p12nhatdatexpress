<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use App\Jobs\SendEmailCampaignNow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mail;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_TransportException;

class EmailCampaignSetTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'EmailCampaignSetTime:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $getEmailCampaign = DB::table('admin_mail_campaign')
            ->where('admin_mail_campaign.start_date', '<', Carbon::now()->timestamp)
            ->where('admin_mail_campaign.total_receipt_mail', '=', 0)
            ->where('admin_mail_campaign.is_deleted', '=', 0)
            ->get();
        if ($getEmailCampaign != null) {
            foreach ($getEmailCampaign as $items) {
                //lấy ra các mail để gửi
                $getEmailCampaignDetail = DB::table('admin_mail_campaign_detail')->where('admin_campaign_id', '=', $items->id)->get();
                //lấy mẫu mail gửi
                $getEmailTemplate = DB::table('admin_mail_template')
                    ->where('id', '=', $items->mail_template_id)
                    ->where('is_deleted', '=', 0)
                    ->first();
                foreach ($getEmailCampaignDetail as $item_details) {
                    //lấy tên người nhận mail để replace
                    $getNameUser = DB::table('user')
                        ->join('user_detail', 'user_detail.user_id', 'user.id')
                        ->where('user.email', '=', $item_details->user_email)->first();
                    //chuyển %ten_nguoi_nhan% thành tên người nhận
                    //chuyển tiêu đề mail
                    $titleEmailReplace = str_replace("%ten_nguoi_nhan%", $getNameUser->username, $getEmailTemplate->template_title);
                    //chuyển nội dung mail
                    $contentEmailReplace = str_replace("%ten_nguoi_nhan%", $getNameUser->username, $getEmailTemplate->template_content);
                    //lấy mail config
                    $getEmailConfig = DB::table('admin_mail_config')
                        ->where('id', '=', $item_details->admin_mail_config_id)
                        ->first();
                    try {
                        //Bỏ thông tin mail config vào swift smtp
                        $transport = (new Swift_SmtpTransport($getEmailConfig->mail_host, $getEmailConfig->mail_port))
                            ->setUsername($getEmailConfig->mail_username)->setPassword($getEmailConfig->mail_password)->setEncryption($getEmailConfig->mail_encryption);
                        $mailer = new Swift_Mailer($transport);
                        //thiết lập tiêu đề, nội dung mail gửi
                        $message = (new Swift_Message($titleEmailReplace))
                            ->setFrom($getEmailConfig->mail_username)
                            ->setTo($item_details->user_email)
                            ->addPart(
                                $contentEmailReplace,
                                'text/html'
                            );
                        $mailer->send($message);
                    } catch (Swift_TransportException $transportExp) {
                        //update trạng thái gửi thất bại
                        DB::table('admin_mail_campaign_detail')->where('id', $item_details->id)->update(['receipt_status' => 2, 'receipt_time' => strtotime('now'),]);
                    }
                    //update trạng thái gửi thành công
                    DB::table('admin_mail_campaign_detail')->where('id', $item_details->id)->update(['receipt_status' => 1, 'receipt_time' => strtotime('now'),]);
                    //cộng vào số mail đã gửi trong chiến dịch
                    $total_receipt_mail = DB::table('admin_mail_campaign')->where('id', $item_details->admin_campaign_id)->first();
                    DB::table('admin_mail_campaign')->where('id', $item_details->admin_campaign_id)->update(['total_receipt_mail' => $total_receipt_mail->total_receipt_mail + 1]);
                    //15s gửi 1 mail
                    sleep(15);
                }
                //send mail
            }
        }
    }
}
