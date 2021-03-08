<?php

namespace app\models;

use app\core\Model;

class RegisterModel extends Model {

    public string $username = '';
    public string $password = '';
    public string $passwordConfirm = '';
    public string $email = '';
    public string $emailConfirm = '';

    public function register() {
        echo 'Creating new user';
    }

    public function rules(): array {
        return [
            'username' => [self::RULE_REQUIRED, [self::RULE_PATTERN, 'pattern' => '/[a-zA-Z0-9]{4,16}/']],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'passwordConfirm' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'emailConfirm' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'email']]
        ];
    }

}