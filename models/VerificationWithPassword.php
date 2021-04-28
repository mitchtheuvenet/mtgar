<?php

namespace app\models;

use app\core\DbModel;
use app\core\Application;

class VerificationWithPassword extends DbVerification {

    public string $password = '';

    public function rules(): array {
        $rules = parent::rules();

        $rules['password'] = [
            self::RULE_REQUIRED,
            [self::RULE_MAX, 'max' => 255]
        ];

        return $rules;
    }

    public function labels(): array {
        return [
            'password' => 'Password'
        ];
    }

    public function sendCode(int $type, string $newEmail = ''): bool {
        $user = Application::$app->user;

        if (!password_verify($this->password, $user->password)) {
            return false;
        }

        $this->email = $user->email;

        return parent::sendCode($type, $newEmail);
    }

}