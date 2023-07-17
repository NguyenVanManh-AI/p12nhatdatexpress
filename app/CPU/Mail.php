<?php

namespace App\CPU;

use Illuminate\Support\Facades\DB;
use Swift_TransportException;
use Swift_SmtpTransport;
use Swift_Message;
use Swift_Mailer;

class Mail
{
    private $email;
    private $title;
    private $message;
    private $emailConfig;

    public function __construct($emailConfig, $email, $title, $message)
    {
        $this->email = $email;
        $this->title = $title;
        $this->message = $message;
        $this->emailConfig = $this->emailConfig;
    }

    public function send()
    {
        try {
            if ($this->mailConfig) {
                $transport = new Swift_SmtpTransport($this->mailConfig['mail_host'], $this->mailConfig['mail_port'], $this->mailConfig['mail_encryption']);
                $transport->setUsername($this->mailConfig['mail_username'])->setPassword($this->mailConfig['mail_password']);

                $content = view('mail.user.active-account', ['title' => $this->title,'message' => $this->message])->render();

                $message = new Swift_Message($this->title);
                $message->setFrom([$this->mailConfig['mail_username'] => 'NhaDatExpress'])->setTo($this->email);
                $message->setBody(view('mail.mail-template'), $content, 'text/html');

                $mailer = new Swift_Mailer($transport);
                $mailer->send($message);
                return true;

            }


        } catch (Swift_TransportException $exception) {
            return false;

        }

        return false;
    }

}
