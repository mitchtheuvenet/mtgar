<?php

namespace app\models;

use app\core\VerificationModel;
use app\core\Application;

class PasswordReset extends VerificationModel {

    protected int $verificationType = DbVerification::TYPE_PASSWORD_RESET;

    public string $password = '';
    public string $passwordConfirm = '';

    public function rules(): array {
        return [
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
        $labels = parent::labels();

        $labels['password'] = 'New password';
        $labels['passwordConfirm'] = 'Confirm new password';

        return $labels;
    }

    public function apply(): bool {
        $this->activeVerification->used = true;
        $this->user->password = password_hash($this->password, PASSWORD_BCRYPT);

        return $this->activeVerification->update(['used']) && $this->user->update(['password']);
    }

}