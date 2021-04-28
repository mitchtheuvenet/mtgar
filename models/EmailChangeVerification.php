<?php

namespace app\models;

use app\core\VerificationModel;

use app\models\DbUser;

class EmailChangeVerification extends VerificationModel {

    public string $newEmail;

    public function __construct() {
        $this->verificationType = DbVerification::TYPE_EMAIL_CHANGE;
    }

    public function rules(): array {
        return [
            'newEmail' => [
                self::RULE_REQUIRED,
                self::RULE_EMAIL,
                [self::RULE_MAX, 'max' => 255],
                [self::RULE_UNIQUE, 'class' => DbUser::class]
            ]
        ];
    }

    public function confirm(): bool {
        $this->activeVerification->used = true;
        $this->user->email = $this->newEmail;

        return $this->activeVerification->update(['used']) && $this->user->update(['email']);
    }

}