<?php

namespace app\models;

use app\core\VerificationModel;
use app\core\Application;

use app\models\DbUser;

class EmailVerification extends VerificationModel {

    protected int $verificationType = DbVerification::TYPE_EMAIL;

    public string $email;

    public string $verificationCode = '';

    public function rules(): array {
        $digits = self::getCodeDigits();

        return [
            'verificationCode' => [
                self::RULE_REQUIRED,
                [
                    self::RULE_PATTERN,
                    'pattern' => "/[0-9]{{$digits}}/",
                    'description' => "a {$digits}-digit numerical code"
                ]
            ]
        ];
    }

    public function labels(): array {
        return [
            'verificationCode' => 'Verification code'
        ];
    }

    public function confirm(): bool {
        $this->activeVerification->used = true;
        $this->user->status = DbUser::STATUS_ACTIVE;

        return $this->activeVerification->update(['used']) && $this->user->update(['status']);
    }

}