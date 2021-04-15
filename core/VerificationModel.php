<?php

namespace app\core;

use app\models\DbUser;
use app\models\DbVerification;

abstract class VerificationModel extends Model {

    public string $email;

    public string $verificationCode = '';

    protected DbUser $user;
    protected DbVerification $activeVerification;

    protected int $verificationType;

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

    public function validate(): bool {
        $where = ['email' => ['value' => $this->email]];

        if ($this->verificationType === DbVerification::TYPE_REGISTRATION) {
            $where['status'] = ['value' => DbUser::STATUS_INACTIVE];
        }

        $this->user = DbUser::findObject($where);
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

                    switch ($this->verificationType) {
                        case DbVerification::TYPE_REGISTRATION:
                            $callToAction = '<a href="/register">register again</a> and use the code before it expires.';
                            break;
                        case DbVerification::TYPE_PASSWORD_RESET:
                            $callToAction = '<a href="/login/forgot?email=' . $this->email . '">request a new code</a> and try again.';
                            break;
                        case DbVerification::TYPE_EMAIL_CHANGE:
                            $callToAction = '<a href="/profile/change/email">request a new code</a> and try again.';
                            break;
                        case DbVerification::TYPE_ACCOUNT_DELETION:
                            $callToAction = '<a href="/profile/delete">request a new code</a> and try again.';
                    }

                    $this->addCustomError('verificationCode', 'This verification code has expired. Please ' . $callToAction);
                } else {
                    $this->addCustomError('verificationCode', 'Something went wrong while verifying your code. Please try again later.');
                }
            }
        }

        return parent::validate();
    }

    public static function getCodeDigits(): string {
        return strval(DbVerification::CODE_LENGTH);
    }

}