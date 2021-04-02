<?php

namespace app\models;

use app\core\Model;
use app\core\Application;

class PasswordReset extends Model {

    public string $email = '';

    public string $verificationCode = '';
    public string $password = '';
    public string $passwordConfirm = '';

    private DbUser $user;
    private DbVerification $activeVerification;

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

    public function validate(): bool {
        $this->user = DbUser::findObject(['email' => $this->email]);
        $this->activeVerification = DbVerification::findActiveVerification($this->user->id, DbVerification::TYPE_PASSWORD_RESET);

        $codeDigits = $this->getCodeDigits();

        if (!empty($this->verificationCode) && preg_match("/[0-9]{{$codeDigits}}/", $this->verificationCode)) {
            if (md5($this->verificationCode) !== $this->activeVerification->code) {
                $this->addCustomError('verificationCode', 'This verification code is invalid.');
    
                return parent::validate();
            }
    
            $timePassed = time() - strtotime($this->activeVerification->created_at);
    
            if ($timePassed >= DbVerification::CODE_EXPIRE) {
                $this->activeVerification->expired = true;
    
                if ($this->activeVerification->update(['expired'])) {
                    $this->addCustomError('verificationCode', 'This verification code has expired. Please <a href="/login/forgot?email=' . $this->email . '">request a new code</a> and try again.');
                } else {
                    $this->addCustomError('verificationCode', 'Unknown error.');
                }
            }
        }

        return parent::validate();
    }

    public function apply(): bool {
        $this->activeVerification->used = true;
        $this->user->password = password_hash($this->password, PASSWORD_BCRYPT);

        return $this->activeVerification->update(['used']) && $this->user->update(['password']);
    }

    public function getCodeDigits(): string {
        return strval(DbVerification::CODE_LENGTH);
    }

}