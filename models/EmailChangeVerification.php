<?php

namespace app\models;

use app\core\VerificationModel;

class EmailChangeVerification extends VerificationModel {

    public string $newEmail;

    public function __construct() {
        $this->verificationType = DbVerification::TYPE_EMAIL_CHANGE;
    }

    public function confirm(): bool {
        $this->activeVerification->used = true;
        $this->user->email = $this->newEmail;

        return $this->activeVerification->update(['used']) && $this->user->update(['email']);
    }

}