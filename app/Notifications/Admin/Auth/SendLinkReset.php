<?php

namespace App\Notifications\Admin\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class SendLinkReset extends Notification
{
    use Queueable;

    protected $admin;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($admin)
    {
        $this->admin = $admin;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $reset_url = URL::temporarySignedRoute('admin.reset_password', now()->addMinutes(30), ['admin' => $this->admin->id]);
        return (new MailMessage)
                    ->subject("Đặt lại mật khẩu")
                    ->line('Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản')
                    ->line('Click vào nút bên dưới để tiến hành đặt lại mật khẩu')
                    ->line('Link đặt lại mật khẩu chỉ tồn tại trong 30 phút')
                    ->action('Đặt lại mật khẩu', $reset_url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
