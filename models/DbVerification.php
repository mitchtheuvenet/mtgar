<?php

namespace app\models;

use app\core\DbModel;
use app\core\Application;

class DbVerification extends DbModel {

    public const TYPE_REGISTRATION = 0;
    public const TYPE_PASSWORD_RESET = 1;

    public const CODE_EXPIRE_HOURS = 1;
    public const CODE_LENGTH = 6;

    public string $email = '';

    public int $user;
    public int $type;
    public string $code;

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
        $userObject = DbUser::findObject(['email' => $this->email]);

        $this->user = $userObject->id;
        $this->type = $type;

        $existingCode = self::findObject([
            'user' => $this->user,
            'type' => $this->type,
            'used' => false,
            'expired' => false,
        ]);

        if (!empty($existingCode)) {
            try {
                $existingCode->expired = true;

                $existingCode->update(['expired']);
            } catch (\Exception $e) {
                // TODO: add exception handling

                return false;
            }
        }

        $codePlain = $this->generateCode();
        $this->code = md5($codePlain);

        $name = $userObject->username;

        $to = ['email' => $userObject->email, 'name' => $name];
        $subject = $this->mailSubject();
        $body = $this->mailBody($name, $codePlain);

        try {
            if (parent::save()) {
                Application::$app->mailer->sendNoReplyMail($to, $subject, $body);

                return true;
            }
        } catch (\Exception $e) {
            // TODO: add exception handling

            return false;
        }         
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

        if ($this->type === self::TYPE_REGISTRATION) {
            $subject .= 'Registration';
        } else if ($this->type === self::TYPE_PASSWORD_RESET) {
            $subject .= 'Password reset';
        }

        $subject .= ' verification code';

        return $subject;
    }

    private function mailBody(string $username, string $code): string {
        $expire = date('Y-m-d G:i T', time() + self::CODE_EXPIRE_HOURS * 3600); // 1 hour = 3600 seconds

        $body =     "Hello {$username},\n";
        $body .=    "\n";
        $body .=    "Your verification code is: <strong>{$code}</strong>.\n";
        $body .=    "\n";
        $body .=    "This code will expire at <strong>{$expire}</strong>. Please use it before then.\n";
        $body .=    "\n";
        $body .=    "Kind regards,\n";
        $body .=    "\n";
        $body .=    "The MTGAR Staff";

        return $body;
    }

}