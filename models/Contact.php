<?php

namespace app\models;

use app\core\Application;
use app\core\Model;

class Contact extends Model {

    public string $email;

    public string $subject = '';
    public string $body = '';

    public function __construct() {
        $this->email = Application::$app->user->email ?? '';
    }

    public function labels(): array {
        return [
            'email' => 'E-mail address',
            'subject' => 'Subject',
            'body' => 'Content'
        ];
    }

    public function rules(): array {
        return [
            'email' => [
                self::RULE_REQUIRED,
                self::RULE_EMAIL,
                [self::RULE_MAX, 'max' => 255]
            ],
            'subject' => [
                self::RULE_REQUIRED,
                [self::RULE_MAX, 'max' => 60]
            ],
            'body' => [
                self::RULE_REQUIRED
            ]
        ];
    }

    public function send() {
        return true;
    }

}