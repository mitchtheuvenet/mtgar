<?php

namespace app\models;

use app\core\Application;
use app\core\Model;

class Contact extends Model {

    public string $email;
    public string $name;

    public string $subject = '';
    public string $body = '';

    public function __construct() {
        $this->email = Application::$app->user->email ?? '';
        $this->name = Application::$app->user->username ?? '';
    }

    public function labels(): array {
        return [
            'email' => 'E-mail address',
            'name' => 'Name',
            'subject' => 'Subject',
            'body' => 'Message'
        ];
    }

    public function rules(): array {
        return [
            'email' => [
                self::RULE_REQUIRED,
                self::RULE_EMAIL,
                [self::RULE_MAX, 'max' => 255]
            ],
            'name' => [
                self::RULE_REQUIRED,
                [self::RULE_MAX, 'max' => 255]
            ],
            'subject' => [
                self::RULE_REQUIRED,
                [self::RULE_MAX, 'max' => 60]
            ],
            'body' => [
                self::RULE_REQUIRED,
                [self::RULE_MAX, 'max' => 500]
            ]
        ];
    }

    public function send() {
        try {
            Application::$app->mailer->sendContactMail($this->from(), $this->subject, $this->body);

            return true;
        } catch (\Exception $e) {
            // TODO: add exception handling

            return false;
        }
    }

    private function from() {
        return [
            'email' => $this->email,
            'name' => $this->name
        ];
    }

}