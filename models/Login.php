<?php

namespace app\models;

use app\core\Model;
use app\core\Application;

class Login extends Model {

    public string $username = '';
    public string $password = '';

    public function rules(): array {
        return [
            'username' => [
                self::RULE_REQUIRED,
                [self::RULE_MAX, 'max' => 255]
            ],
            'password' => [
                self::RULE_REQUIRED,
                [self::RULE_MAX, 'max' => 255]
            ]
        ];
    }

    public function labels(): array {
        return [
            'username' => 'Username',
            'password' => 'Password'
        ];
    }

    public function logIn() {
        $user = DbUser::findObject(['username' => ['value' => $this->username]]);

        if (empty($user) || !password_verify($this->password, $user->password)) {
            return false;
        }

        return Application::$app->logIn($user);
    }

}