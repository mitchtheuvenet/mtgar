<?php

namespace app\core;

use app\models\DbUser;
use app\models\DbVerification;

abstract class VerificationModel extends Model {

    protected DbUser $user;
    protected DbVerification $activeVerification;

    public function validate(): bool {
        $this->user = DbUser::findObject(['email' => $this->email]);
        $this->activeVerification = DbVerification::findActiveVerification($this->user->id, $this->verificationType);

        $codeDigits = self::getCodeDigits();

        if (!empty($this->verificationCode) && preg_match("/[0-9]{{$codeDigits}}/", $this->verificationCode)) {
            if (md5($this->verificationCode) !== $this->activeVerification->code) {
                $this->addCustomError('verificationCode', 'This verification code is invalid.');
    
                return parent::validate();
            }
    
            $timePassed = time() - strtotime($this->activeVerification->created_at);
    
            if ($timePassed >= DbVerification::CODE_EXPIRE) {
                $this->activeVerification->expired = true;

                if ($this->activeVerification->update(['expired'])) {
                    $callToAction = '';

                    if ($this->verificationType === DbVerification::TYPE_EMAIL) {
                        $callToAction = '<a href="/register">register again</a> and use the code before it expires.';
                    } else if ($this->verificationType === DbVerification::TYPE_PASSWORD_RESET) {
                        $callToAction = '<a href="/login/forgot?email=' . $this->email . '">request a new code</a> and try again.';
                    }

                    $this->addCustomError('verificationCode', 'This verification code has expired. Please ' . $callToAction);
                } else {
                    $this->addCustomError('verificationCode', 'Unknown error.');
                }
            }
        }

        return parent::validate();
    }

    public static function getCodeDigits(): string {
        return strval(DbVerification::CODE_LENGTH);
    }

}