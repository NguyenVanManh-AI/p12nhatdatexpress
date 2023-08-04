<?php

namespace App\Services;

use App\Models\AdminMailTemplate;
use App\Models\UserMailTemplate;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class MailService
{
    /**
     * Get template content for admin mail template with action
     * @param string $action
     * @param array $replaceData = []
     *
     * @return AdminMailTemplate $mailTemplate
     */
    public function getContent($action, array $replaceData = [])
    {
        $mailTemplate = AdminMailTemplate::firstWhere('template_action', $action);
        if ($mailTemplate) {
            $mailTemplate = $this->setVariables($mailTemplate,$replaceData);
        }
        return $mailTemplate;
    }

    /**
     * Get template content for user mail template
     * @param UserMailTemplate $template
     * @param array $replaceData = []
     *
     * @return UserMailTemplate $mailTemplate
     */
    public function getUserTemplateContent(UserMailTemplate $template, array $replaceData = [])
    {
        $mailTemplate = $this->setVariables($template, $replaceData, false);

        return $mailTemplate;
    }

    /**
     * Set variable for template subject and content
     * @param $mail
     * @param array $data = []
     * @param $isAdmin = true
     *
     * @return $mail
     */
    private function setVariables($mail, array $data = [], $isAdmin = true)
    {
        $user = data_get($data, 'user');
        $customer = data_get($data, 'customer');
        $activeDepositToken = data_get($data, 'one_time_confirm_token');

        $subject = $isAdmin ? $mail->template_title : $mail->mail_header;
        $content = $isAdmin ? $mail->template_content : $mail->mail_content;

        if ($user) {
            // title
            $subject = str_replace('%ten_nguoi_dung%', data_get($user, 'detail.fullname'), $subject);
            $subject = str_replace('%ten_nguoi_nhan%', data_get($user, 'detail.fullname'), $subject);

            // content
            $content = str_replace('%ten_nguoi_dung%', data_get($user, 'detail.fullname'), $content);
            $content = str_replace('%ten_nguoi_nhan%', data_get($user, 'detail.fullname'), $content);
        }

        if ($customer) {
            // title
            $subject = str_replace('%ten_nguoi_dung%', $customer->fullname, $subject);
            $subject = str_replace('%ten_nguoi_nhan%', $customer->fullname, $subject);

            // content
            $content = str_replace('%ten_nguoi_dung%', $customer->fullname, $content);
            $content = str_replace('%ten_nguoi_nhan%', $customer->fullname, $content);
        }

        if ($activeDepositToken) {
            $active_link = route('active-deposit', ['verify_code' => $activeDepositToken, 'email' => data_get($data, 'email')]);
            $content = str_replace('%link%', $active_link, $content);
        }

        $subject = $this->replaceRandomVariable($subject);
        $content = $this->replaceRandomVariable($content);

        if ($isAdmin) {
            $mail->template_title = $subject;
            $mail->template_content = $content;
        } else {
            $mail->mail_header = $subject;
            $mail->mail_content = $content;
        }

        return $mail;
    }

    private function replaceRandomVariable($content)
    {
        preg_match_all('/\{[^\}]+\}/', $content, $matches);
        $matches = data_get($matches, '0', []);

        foreach ($matches as $match) {
            $allContent = preg_replace('/\{|\}/', '', $match);
            $contentArr = explode('|', $allContent);

            $randomContent = trim($contentArr[array_rand($contentArr)]);

            $content = str_replace($match, $randomContent, $content);
        }

        return $content;
    }

    /**
     * Send test mail
     */
    public function sendMailTest(
        $mailHost,
        $mailPort,
        $mailEncrypt,
        $mailUser,
        $mailPassword,
        $email,
        $mailTitle,
        $mailMessage
    ) {
        $transport = new Swift_SmtpTransport($mailHost, $mailPort, $mailEncrypt);
        $transport->setUsername($mailUser)->setPassword($mailPassword);
        $message = new Swift_Message($mailTitle);
        $message->setFrom($mailUser)
            ->setTo($email)
            ->setBody(view('mail.mail-template', ['title' => $mailTitle, 'message' => $mailMessage])->render(), 'text/html');
        $mailer = new Swift_Mailer($transport);
        $mailer->send($message);
    }
}

