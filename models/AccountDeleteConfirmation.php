<?php

namespace app\models;

use app\core\VerificationModel;

use app\models\DbUser;

class AccountDeleteConfirmation extends VerificationModel {

    public function __construct() {
        $this->verificationType = DbVerification::TYPE_ACCOUNT_DELETION;
    }

    public function confirm(): bool {
        $this->activeVerification->used = true;
        $this->user->status = DbUser::STATUS_DELETED;

        return $this->activeVerification->update(['used']) && $this->user->update(['status']);
    }

}