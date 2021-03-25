<?php

namespace app\core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    private const SMTP_PORT = 587; // TLS

    private string $host;
    private string $username;
    private string $password;

    public function __construct(array $config) {
        $this->host = $config['host'] ?? '';
        $this->username = $config['user'] ?? '';
        $this->password = $config['pass'] ?? '';
    }

    private function defaultAddress() {
        return [
            'email' => $this->username,
            'name' => 'MTG Akashic Records'
        ];
    }

    private function initMail() {
        $mail = new PHPMailer(true); // enable exceptions

        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = $this->host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->username;
        $mail->Password = $this->password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = self::SMTP_PORT;

        return $mail;
    }

    public function sendMail(string $subject, string $body, array $from = [], bool $reply = false, array $to = []) {
        if (empty($from)) {
            $from = $this->defaultAddress();
        }
        
        if (empty($to)) {
            $to = $this->defaultAddress();
        }

        $mail = $this->initMail();

        $mail->setFrom($this->username, $from['name']);
        $mail->addAddress($to['email'], $to['name']);

        if ($reply) {
            $mail->addReplyTo($from['email'], $from['name']);
        }

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body; // TODO: text formatting

        $mail->send();

        return true;
    }

}