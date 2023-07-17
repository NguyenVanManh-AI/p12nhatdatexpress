<?php

namespace App\Listeners;
use App\Events\Admin\Auth\SendLinkReset;
use Brian2694\Toastr\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Swift_Image;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_TransportException;

class SendLinkResetLister implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SendLinkReset $event
     * @return bool
     */
    public function handle(SendLinkReset $event): bool
    {
        $reset_url = URL::temporarySignedRoute('admin.reset_password', now()->addMinutes(30), ['admin' => $event->admin->id]);

        $getEmailConfig = DB::table('admin_mail_config')
            ->where('is_deleted', 0)
            ->where('is_config', 1)
            ->first();

        try {
            //Bỏ thông tin mail config vào swift smtp
            $transport = new Swift_SmtpTransport($getEmailConfig->mail_host, $getEmailConfig->mail_port, $getEmailConfig->mail_encryption);
            $transport->setUsername($getEmailConfig->mail_username)->setPassword($getEmailConfig->mail_password);

            $message = new Swift_Message("Đặt lại mật khẩu");
            $logo = $message->embed(Swift_Image::fromPath(asset('frontend/images/logo.png')));

            $message_content = view('mail.admin.forgot-password', ['url' => $reset_url, 'logo' => $logo])->render();

            $message->setFrom([$getEmailConfig->mail_username => "Nhà đất Express"])
                ->setTo($event->admin->admin_email)
                ->setBody($message_content, 'text/html');

            $mailer = new Swift_Mailer($transport);
            $mailer->send($message);
        } catch (Swift_TransportException $transportExp) {
            Toastr::error("Gửi mail thất bại");
        }

        return true;
    }
}
