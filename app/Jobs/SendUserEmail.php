<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Swift_TransportException;
use Swift_SmtpTransport;
use Swift_Message;
use Swift_Mailer;

class SendUserEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mailHost;
    public $mailPort;
    public $mailEncrypt;
    public $mailUser;
    public $mailPassword;
    public $email;
    public $mailTitle;
    public $mailMessage;

    /**
     * Create a new job instance.public
     *
     * @return void
     */
    public function __construct($mailHost, $mailPost, $mailEncrypt, $mailUser, $mailPassword, $email, $mailConfig, $mailTitle, $mailMessage)
    {
        $this->mailHost = $mailHost;
        $this->mailPort = $mailPost;
        $this->mailEncrypt = $mailEncrypt;
        $this->mailUser = $mailUser;
        $this->mailPassword = $mailPassword;

        $this->email = $email;
        $this->mailTitle = $mailTitle;
        $this->mailMessage = $mailMessage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $transport = new Swift_SmtpTransport($this->mailHost, $this->mailPort, $this->mailEncrypt);
            $transport->setUsername($this->mailUser)->setPassword($this->mailPassword);
            $message = new Swift_Message($this->mailTitle);
            $message->setFrom($this->mailUser)
                ->setTo($this->email)
                ->setBody(view('mail.mail-template', ['title' => $this->mailTitle, 'message' => $this->mailMessage])->render(), 'text/html');
            $mailer = new Swift_Mailer($transport);
            $mailer->send($message);


        } catch (Swift_TransportException $exception) {

        }
    }
}
