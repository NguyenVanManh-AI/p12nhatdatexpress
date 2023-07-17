<?php

namespace App\Jobs;

use App\Enums\AdvisoryStatus;
use App\Models\AdminMailConfig;
use App\Models\Classified\Classified;
use App\Models\Event\Event;
use App\Models\ModuleAdvisory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_TransportException;
use Throwable;

class SendAdvisoryJob implements ShouldQueue // ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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
        $advisory = ModuleAdvisory::find($this->id);

        if (!$advisory || $advisory->status !== AdvisoryStatus::PENDING) return;

        $mailConfig = AdminMailConfig::first();
        $advisoryable = $advisory->advisoryable;

        // should tracking sended and limit it
        if ($mailConfig && $advisoryable) {
            $host = $mailConfig->mail_host;
            $port = $mailConfig->mail_port;
            $encryption = $mailConfig->mail_encryption;
            $userName = $mailConfig->mail_username;
            $password = $mailConfig->mail_password;

            if (get_class($advisoryable) == Classified::class) {
                $sentToMail = $advisoryable->contact_email;
            } elseif (data_get($advisoryable->user, 'email')) {
                $sentToMail = data_get($advisoryable->user, 'email');
            }

            if (!$sentToMail) return;

            $params = [
                'fullname' => $advisory->fullname,
                'phone_number' => $advisory->phone_number,
                'email' => $advisory->email,
                'note' => $advisory->note,
                'registration' => [
                    'url' => data_get($advisory->options, 'registration.url'),
                    'name' => data_get($advisory->options, 'registration.name'),
                ],
                'created_at' => $advisory->created_at
            ];

            $titleType = '';
            switch (get_class($advisoryable)) {
                case Classified::class:
                    $titleType = 'tin rao';
                    break;
                case Event::class:
                    $titleType = 'sự kiện';
                    break;
                default:
                    break;
            }
            $mailTitle = "Liên hệ tư vấn $titleType";

            // should create new job for send this mail for update advisory status
            $mailMessages = view('components.home.user.partials._send-advisory-mail', [
                'params' => $params,
            ])->render();

            try {
                $transport = new Swift_SmtpTransport($host, $port, $encryption);
                $transport->setUsername($userName)->setPassword($password);
                $message = new Swift_Message($mailTitle);
                $message->setFrom($userName)
                    ->setTo($sentToMail)
                    ->setBody(view('mail.mail-template',
                        [
                            'title' => $mailTitle,
                            'message' => $mailMessages
                        ])->render(), 'text/html');
                $mailer = new Swift_Mailer($transport);
                $mailer->send($message);

                // sended update advisory status
                $options = $advisory->options ?: [];
                $options['mail'] = [
                    'title' => $mailTitle,
                    'sended_name' => $userName,
                    'send_to' => $sentToMail,
                ];
    
                $advisory->update([
                    'status' => AdvisoryStatus::SENDED,
                    'options' => $options
                ]);
            } catch (Swift_TransportException $exception) {
                $advisory->update([
                    'status' => AdvisoryStatus::FAILED,
                ]);
            }
        }

        return 0;
    }

    public function failed(Throwable $exception): void
    {
        $advisory = ModuleAdvisory::find($this->id);

        if (!$advisory) return;

        $advisory->update([
            'status' => AdvisoryStatus::FAILED,
        ]);
    }
}
