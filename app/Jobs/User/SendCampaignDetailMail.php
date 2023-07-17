<?php

namespace App\Jobs\User;

use App\Enums\User\CampaignStatus;
use App\Models\UserMailCampaign;
use App\Models\UserMailCampaignDetail;
use App\Services\MailService;
use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_TransportException;
use Throwable;

class SendCampaignDetailMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;
    private MailService $mailService;

    /**
     * Create a new job instance.public
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->mailService = new MailService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $detail = UserMailCampaignDetail::find($this->id);
        $customer = $detail->customer;

        if (!$detail
                || !$detail->userMailCampaign
                || $detail->userMailCampaign->status === CampaignStatus::CANCELLED
                || !$detail->userMailCampaign->template
                || !$detail->userMailConfig
                || !$customer
                || $detail->receipt_status == 3) return;

        // Customer hủy đăng ký nhận email từ user này
        if ($customer->is_disabled_notification) {
            $detail->update([
                'receipt_status' => 3, // Đã hủy đăng ký
                'receipt_time' => time()
            ]);

            return;
        }

        $config = $detail->userMailConfig;
        $userName = $config->mail_username;

        $replaceData = [
            'customer' => $customer
        ];
        $mailTemplate = $this->mailService->getUserTemplateContent($detail->userMailCampaign->template, $replaceData);
        
        try {
            $transport = new Swift_SmtpTransport($config->mail_host, $config->mail_port, $config->mail_encription);
            $transport->setUsername($userName)->setPassword($config->mail_password);
            $message = new Swift_Message($mailTemplate->mail_header);
            $message->setFrom($userName)
                ->setTo($customer->email)
                ->setBody(
                    view('mail.mail-campaign-template', [
                        'title' => $mailTemplate->mail_header,
                        'message' => $mailTemplate->mail_content,
                        'unsubscribeLink' => route('unsubscribe-campaign-mail', $customer->disabled_notification_token)
                    ])->render(),
                    'text/html'
                );
            $mailer = new Swift_Mailer($transport);
            $mailer->send($message);

            $detail->update([
                'receipt_status' => 1, // Đã gửi
                'receipt_time' => time()
            ]);

            $detail->userMailConfig->increment('num_mail');
        } catch (Swift_TransportException $exception) {
            $detail->update([
                'receipt_status' => 2, // Lỗi
                'receipt_time' => time()
            ]);

            $this->checkCancelledCampaign($detail->userMailCampaign);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        $detail = UserMailCampaignDetail::find($this->id);

        if (!$detail) return;

        $detail->update([
            'receipt_status' => 2, // Lỗi
            'receipt_time' => time()
        ]);
        
        $this->checkCancelledCampaign($detail->userMailCampaign);
    }

    private function checkCancelledCampaign(UserMailCampaign $campaign)
    {
        if ($campaign->details()->where('receipt_status', 2)->count() >= 3) {
            $campaign->update([
                'status' => CampaignStatus::CANCELLED
            ]);
        }
    }
}
