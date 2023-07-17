<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Swift_TransportException;

class CronAdminMailCampaign extends Command
{
    private const SENDER = "Nhà đất Express"; // Chiến dịch của admin nói chung
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin_mail_campaign:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Admin mail campaign';

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
        // Get campaigns
        $campaigns = DB::table('admin_mail_campaign')
            ->where('is_deleted', 0)
            ->where('start_date', '<=', time())
            ->where(function ($query) {
                $query->where('is_action', 0)
                    ->orWhere('is_birthday', 1);
            })
            ->orderBy('id')
            ->get();

        // Loop campaign
        foreach ($campaigns as $campaign) {
            # Lay message email template
            $mail_message = DB::table('admin_mail_template')
                ->where('is_deleted', 0)
                ->where('id', $campaign->mail_template_id)
                ->first();

            # Lấy các email config của admin tạo chiến dịch
            $send_emails = DB::table('admin_mail_config')
                ->where('is_deleted', 0)
                ->where('is_config', 0)
                ->get();

            # Xác định loại gửi cho user hay customer
            if ($campaign->is_customer) {
                $this->send_customer($campaign, $send_emails, $mail_message);
            }
            else {
                $this->send_user($campaign, $send_emails, $mail_message);
            }

            #Đưa chiến dịch về trạng thái đã thực hiện
            if (!$campaign->is_birthday) {
                DB::table('admin_mail_campaign')
                    ->where('id', $campaign->id)
                    ->update(['is_action' => 1]);
            }
        }

        return 0;
    }

    //---------------------------------------------SUPPORT METHOD-----------------------------------------------------//
    # Gửi mail cho user
    private function send_user($campaign, $configs, $template){
        # Tìm thành viên thỏa điều kiện của chiến dịch
        $user = DB::table('user')
            ->select('user.id', 'user_detail.fullname', 'user.email', 'acd.id as detail_id')
            ->join('user_detail', 'user_detail.user_id', 'user.id')
            ->join('admin_mail_campaign_detail as acd', 'acd.user_id', '=', 'user.id')
            ->where('admin_campaign_id', $campaign->id);

        $campaign->is_birthday ? $user->whereRaw("from_unixtime(birthday,'%m-%d') = date_format(curdate(), '%m-%d')") : true;

        $user = $user->get();

        $this->send_mail($user, $configs, $template, $campaign, 1);
    }

    # Gửi mail cho khách hàng
    private function send_customer($campaign, $configs, $template){
        # Tìm khách hàng thỏa điều kiện của chiến dịch
        $customer = DB::table('customer as cus')
            ->select('cus.fullname', 'cus.email', 'cus.id')
            ->where('cus.is_deleted', 0);

            $campaign->job_id ? $customer->where('cus.job', $campaign->job_id) : true;
            $campaign->cus_source ? $customer->where('cus.cus_source', $campaign->cus_source) : true;
            $campaign->created_from ? $customer->where('cus.created_at', '>', $campaign->created_from) : true;
            $campaign->created_to ? $customer->where('cus.created_at', '<', $campaign->created_to) : true;
            if($campaign->birthday){
                $customer->whereBetween('cus.birthday', [ $campaign->birthday , $campaign->birthday + 86399 ]);
            }
            if ($campaign->province_id) {
                $customer->join('customer_location as clo', 'cus.id', '=', 'clo.cus_id')
                    ->where('clo.province_id', $campaign->province_id);

                if ($campaign->district_id) {
                    $customer->where('clo.district_id', $campaign->district_id);
                }
            }

        if ($campaign->group_parent_id) {
            $customer->join('classified', 'cus.classified_id', '=', 'classified.id')
                ->join('group', 'classified.group_id', '=', 'group.id')
                ->where('group.parent_id', $campaign->group_parent_id);

            if ($campaign->group_id) {
                $customer->where('group.id', $campaign->group_id);
            }
        }

        $campaign->is_birthday ? $customer->whereRaw("from_unixtime(birthday,'%m-%d') = date_format(curdate(), '%m-%d')") : true;

        $customer = $customer->get();

        $this->send_mail($customer, $configs, $template, $campaign);
    }

    # Send mail
    private function send_mail($receiver , $configs, $template, $campaign, $is_user = 0){
        if (isset($receiver) && isset($configs) && isset($template)) {
            $cus_num = $receiver->count(); # Số mail sẽ gửi
            $send_email_num = $configs->count(); # số email dùng để gửi
            $eve_send_per_mail = (int)ceil($cus_num / $send_email_num); # Số mail mà mỗi email sẽ gửi

            foreach ($configs as $email) {
                $count = 0;
                $transport = new Swift_SmtpTransport($email->mail_host, $email->mail_port, $email->mail_encryption);
                $transport->setUsername($email->mail_username)->setPassword($email->mail_password);

                foreach ($receiver as $key => $value) {
                    if (++$count > $eve_send_per_mail) {
                        break;
                    }

                    $title = str_replace('%ten_nguoi_nhan%', $value->fullname, $template->template_title);
                    $content = str_replace('%ten_nguoi_nhan%', $value->fullname, $template->template_content);

                    $message = new Swift_Message($title);
                    $message->setFrom([$email->mail_username => self::SENDER])
                        ->setTo($value->email)
                        ->setBody($content, 'text/html');

                    unset($receiver[$key]);

                    /*Send mail*/
                    $mailer = new Swift_Mailer($transport);
                    try {
                        $mailer->send($message);
                    }
                    catch (Swift_TransportException $exp){
                        if ($is_user) {
                            DB::table('admin_mail_campaign_detail')->where('id', $value->detail_id)->update(['receipt_status' => 2, 'receipt_time' => strtotime('now')]);
                        }
                    }

                    # Update trạng thái gửi thành công
                    if ($is_user){
                        DB::table('admin_mail_campaign_detail')->where('id', $value->detail_id)->update(['receipt_status' => 1, 'receipt_time' => strtotime('now')]);
                    }

                    # Tăng 1 lượt gửi trên tổng số
                    DB::table('admin_mail_campaign')->where('id', $campaign->id)->increment('total_receipt_mail');

                    sleep(15);
                    /*End send mail*/
                }

                #Cập nhật số mail đã gửi của email config
                $num_send_mail = DB::table('admin_mail_config')->where('id', $email->id)->value('num_mail');
                DB::table('admin_mail_config')->where('id', $email->id)->update(['num_mail' => $num_send_mail + $count]);

            }

        }
    }

}
