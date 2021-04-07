<?php

namespace app\models;

use app\core\Model;
use app\core\Application;

use app\models\DbUser;

class EmailChangeRequest extends Model {

    public string $password = '';
    public string $newEmail = '';
    public string $newEmailConfirm = '';

    public function rules(): array {
        return [
            'password' => [
                self::RULE_REQUIRED,
                [self::RULE_MAX, 'max' => 255]
            ],
            'newEmail' => [
                self::RULE_REQUIRED,
                self::RULE_EMAIL,
                [self::RULE_MAX, 'max' => 255],
                [self::RULE_UNIQUE, 'class' => DbUser::class]
            ],
            'newEmailConfirm' => [
                self::RULE_REQUIRED,
                [self::RULE_MATCH, 'match' => 'newEmail']
            ]
        ];
    }

    public function labels(): array {
        return [
            'password' => 'Password',
            'newEmail' => 'New e-mail address',
            'newEmailConfirm' => 'Confirm new e-mail address'
        ];
    }

    public function send() {
        $user = Application::$app->user;

        if (!password_verify($this->password, $user->password)) {
            return false;
        }

        $oldEmail = $user->email;

        $user->email = $this->newEmail;

        if ($user->deleteInactives(['email'])) {
            $verification = new DbVerification($oldEmail);

            return $verification->sendCode(DbVerification::TYPE_EMAIL_CHANGE, $this->newEmail);
        }

        return false;
    }

}