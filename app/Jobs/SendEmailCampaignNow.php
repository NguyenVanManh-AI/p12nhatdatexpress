<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mail;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_TransportException;


class SendEmailCampaignNow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $id;
    public const SENDER = "Nhà đất Express";

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        //lấy ra chiến dịch mail
        $getEmailCampaign = DB::table('admin_mail_campaign')->where('id', '=', $this->id)->first();

        //lấy ra các mail để gửi
        $getEmailCampaignDetail = DB::table('admin_mail_campaign_detail')->where('admin_campaign_id', '=', $getEmailCampaign->id)->get();


        //lấy mẫu mail gửi
        $getEmailTemplate = DB::table('admin_mail_template')
            ->where('id', '=', $getEmailCampaign->mail_template_id)
            ->first();


        foreach ($getEmailCampaignDetail as $items) {

            //lấy tên người nhận mail để replace
            $getNameUser = DB::table('user')
                ->select('user.id', 'username', 'user_detail.fullname')
                ->join('user_detail', 'user_detail.user_id', 'user.id')
                ->where('user.email', '=', $items->user_email)->first();

            //chuyển %ten_nguoi_nhan% thành tên người nhận
            //chuyển tiêu đề mail
            $titleEmailReplace = str_replace("%ten_nguoi_nhan%", $getNameUser->fullname, $getEmailTemplate->template_title);
            //chuyển nội dung mail
            $contentEmailReplace = str_replace("%ten_nguoi_nhan%", $getNameUser->fullname, $getEmailTemplate->template_content);

            //lấy mail config
            $getEmailConfig = DB::table('admin_mail_config')
                ->where('id', '=', $items->admin_mail_config_id)
                ->where('is_deleted', 0)
                ->where('is_config', 0)
                ->first();

            try {
                //Bỏ thông tin mail config vào swift smtp
                $transport = new Swift_SmtpTransport($getEmailConfig->mail_host, $getEmailConfig->mail_port, $getEmailConfig->mail_encryption);
                $transport->setUsername($getEmailConfig->mail_username)->setPassword($getEmailConfig->mail_password);

                $message = new Swift_Message($titleEmailReplace);
                $message->setFrom([$getEmailConfig->mail_username => self::SENDER])
                    ->setTo($items->user_email)
                    ->setBody($contentEmailReplace, 'text/html');

                $mailer = new Swift_Mailer($transport);
                $mailer->send($message);
            } catch (Swift_TransportException $transportExp) {
                //update trạng thái gửi thất bại
                DB::table('admin_mail_campaign_detail')->where('id', $items->id)->update(['receipt_status' => 2, 'receipt_time' => strtotime('now'),]);
            }

            sleep(15);

            //update trạng thái gửi thành công
            DB::table('admin_mail_campaign_detail')->where('id', $items->id)->update(['receipt_status' => 1, 'receipt_time' => strtotime('now'),]);

            //cộng vào số mail đã gửi trong chiến dịch
            $total_receipt_mail = DB::table('admin_mail_campaign')->where('id', $this->id)->first();
            DB::table('admin_mail_campaign')->where('id', $this->id)
                ->update([
                    'total_receipt_mail' => $total_receipt_mail->total_receipt_mail + 1,
                    'is_action' => 1
                ]);
        }
    }

    public function failed()
    {

    }
}
