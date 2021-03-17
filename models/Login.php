<?php

namespace app\models;

use app\core\Model;

class Login extends Model {

    public string $username = '';
    public string $password = '';

    public function rules(): array {
        return [
            'username' => [self::RULE_REQUIRED],
            'password' => [self::RULE_REQUIRED]
        ];
    }

    public function labels(): array {
        return [
            'username' => 'Username',
            'password' => 'Password'
        ];
    }

    public function logIn() {
        $user = DbUser::findObject(['username' => $this->username]);

        if (empty($user) || !password_verify($this->password, $user->password)) {
            return false;
        }

        echo '<pre>';
        var_dump($user);
        echo '</pre>';
        exit;
    }

}