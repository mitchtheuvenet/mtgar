<?php

namespace app\models;

use app\core\VerificationModel;
use app\core\Application;

use app\models\DbUser;

class RegistrationVerification extends VerificationModel {

    public function __construct() {
        $this->verificationType = DbVerification::TYPE_REGISTRATION;
    }

    public function confirm(): bool {
        $this->activeVerification->used = true;
        $this->user->status = DbUser::STATUS_ACTIVE;

        return $this->activeVerification->update(['used']) && $this->user->update(['status']);
    }

}