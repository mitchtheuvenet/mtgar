<?php

namespace app\models;

use app\core\Model;
use app\core\Application;

class PasswordReset extends Model {

    public string $email = '';

    public string $verificationCode = '';
    public string $password = '';
    public string $passwordConfirm = '';

    public function __construct(string $email = '') {
        $this->email = $email;
    }

    public function rules(): array {
        $digits = $this->getCodeDigits();

        return [
            'verificationCode' => [
                self::RULE_REQUIRED,
                [
                    self::RULE_PATTERN,
                    'pattern' => "/[0-9]{{$digits}}/",
                    'description' => "a {$digits}-digit numerical code"
                ]
            ],
            'password' => [
                self::RULE_REQUIRED,
                [self::RULE_MIN, 'min' => 8],
                [self::RULE_MAX, 'max' => 255]
            ],
            'passwordConfirm' => [
                self::RULE_REQUIRED,
                [self::RULE_MATCH, 'match' => 'password']
            ]
        ];
    }

    public function labels(): array {
        return [
            'verificationCode' => 'Verification code',
            'password' => 'New password',
            'passwordConfirm' => 'Confirm new password'
        ];
    }

    public function apply(): bool {
        // $user = DbUser::findObject();
        // $verification = DbVerification::findObject(['code' => $this->verificationCode]);

        // $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        // if ($user->update())

        return false;
    }

    public function getCodeDigits(): string {
        return strval(DbVerification::CODE_LENGTH);
    }

}