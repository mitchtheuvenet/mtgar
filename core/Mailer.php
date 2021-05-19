<?php

namespace app\core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    private const SMTP_PORT = 465; // SMTPS

    private string $host;
    private string $username;
    private string $password;
    private int $debug;

    public function __construct(array $config) {
        $this->host = $config['host'] ?? '';
        $this->username = $config['user'] ?? '';
        $this->password = $config['pass'] ?? '';
        $this->debug = $config['debug'] ?? SMTP::DEBUG_OFF;
    }

    public function sendNoReplyMail(array $to, string $subject, string $body) {
        $from = $this->siteAddress();

        $this->sendMail($from, $to, $subject, $body);
    }

    public function sendContactMail(array $from, string $subject, string $body) {
        $replyTo = $from;
        $to = $this->siteAddress();
        $from = $to;
        $from['name'] = $replyTo['name'];

        $this->sendMail($from, $to, $subject, $body, $replyTo);
    }

    private function siteAddress(): array {
        return [
            'email' => $this->username,
            'name' => 'MTG Akashic Records'
        ];
    }

    private function initMail(): PHPMailer {
        $mail = new PHPMailer(true); // enable exceptions

        $mail->isSMTP();
        $mail->SMTPDebug = $this->debug;
        $mail->Host = $this->host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->username;
        $mail->Password = $this->password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = self::SMTP_PORT;

        return $mail;
    }

    // private function bodyWithStyling(string $body): string {
    //     return '<div style="">' . $body . '</div>';
    // }

    private function sendMail(array $from, array $to, string $subject, string $body, array $replyTo = []) {
        $mail = $this->initMail();

        $mail->setFrom($from['email'], $from['name']);
        $mail->addAddress($to['email'], $to['name']);

        if (!empty($replyTo)) {
            $mail->addReplyTo($replyTo['email'], $replyTo['name']);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        // $mail->AltBody = $body;

        $mail->send();
    }

}