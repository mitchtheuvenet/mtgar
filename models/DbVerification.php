<?php

namespace app\models;

use app\core\DbModel;
use app\core\Application;

class DbVerification extends DbModel {

    public const TYPE_REGISTRATION = 0;
    public const TYPE_PASSWORD_RESET = 1;
    public const TYPE_EMAIL_CHANGE = 2;
    public const TYPE_ACCOUNT_DELETION = 3;

    public const CODE_EXPIRE = 3600;
    public const CODE_LENGTH = 6;

    public string $email;

    public int $user;
    public int $type;
    public string $code;

    public function __construct(string $email = '') {
        $this->email = $email;
    }

    public static function tableName(): string {
        return 'verifications';
    }

    public static function columnNames(): array {
        return ['user', 'type', 'code'];
    }

    public static function primaryKey(): string {
        return 'id';
    }

    public function rules(): array {
        return [
            'email' => [
                self::RULE_REQUIRED,
                self::RULE_EMAIL,
                [self::RULE_MAX , 'max' => 255],
                [self::RULE_EXISTS, 'class' => DbUser::class]
            ]
        ];
    }

    public function labels(): array {
        return [
            'email' => 'E-mail address'
        ];
    }

    public function sendCode(int $type): bool {
        $where = ['email' => $this->email];

        if ($type === self::TYPE_REGISTRATION) {
            $where['status'] = DbUser::STATUS_INACTIVE;
        }

        $userObject = DbUser::findObject($where);

        $this->user = $userObject->id;
        $this->type = $type;

        $activeVerification = self::findActiveVerification($this->user, $this->type);

        if (!empty($activeVerification)) {
            $activeVerification->expired = true;

            if (!$activeVerification->update(['expired'])) {
                return false;
            }
        }

        $codePlain = $this->generateCode();
        $this->code = md5($codePlain);

        $name = $userObject->username;

        $to = ['email' => $userObject->email, 'name' => $name];
        $subject = $this->mailSubject();
        $body = $this->mailBody($name, $codePlain);

        if (parent::save()) {
            try {
                Application::$app->mailer->sendNoReplyMail($to, $subject, $body);
    
                return true;
            } catch (\Exception $e) {
                // TODO: add exception handling
            }
        }

        return false;
    }

    public static function findActiveVerification(int $userId, int $type) {
        return self::findObject([
            'user' => $userId,
            'type' => $type,
            'used' => false,
            'expired' => false
        ]);
    }

    private function generateCode(): string {
        $code = '';

        for ($i = 0; $i < self::CODE_LENGTH; $i++) {
            $code .= strval(random_int(0, 9));
        }

        return $code;
    }

    private function mailSubject(): string {
        $subject = '';

        switch ($this->type) {
            case self::TYPE_REGISTRATION:
                $subject .= 'Registration';
                break;
            case self::TYPE_PASSWORD_RESET:
                $subject .= 'Password reset';
                break;
            case self::TYPE_EMAIL_CHANGE:
                $subject .= 'New e-mail address';
                break;
            case self::TYPE_ACCOUNT_DELETION:
                $subject .= 'Account deletion';
                
        }

        $subject .= ' verification code';

        return $subject;
    }

    private function mailBody(string $username, string $code): string {
        $expire = date('Y-m-d G:i T', time() + self::CODE_EXPIRE);

        $body =     "<p>Hello {$username},</p>\n";
        
        $body .=    "<p>Your verification code is: <strong>{$code}</strong>.</p>\n";
        
        $body .=    "<p>This code will expire at <strong>{$expire}</strong>. Please use it before then.</p>\n";
        
        $body .=    "<p>Kind regards,</p>\n";
        
        $body .=    "<p>The MTGAR Staff</p>";

        return $body;
    }

}