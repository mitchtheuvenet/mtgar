<?php

namespace app\models;

use app\core\Model;
use app\core\Application;

class PasswordChange extends Model {

    public string $password = '';
    public string $newPassword = '';
    public string $newPasswordConfirm = '';

    public function rules(): array {
        return [
            'password' => [
                self::RULE_REQUIRED,
                [self::RULE_MAX, 'max' => 255]
            ],
            'newPassword' => [
                self::RULE_REQUIRED,
                [self::RULE_MIN, 'min' => 8],
                [self::RULE_MAX, 'max' => 255]
            ],
            'newPasswordConfirm' => [
                self::RULE_REQUIRED,
                [self::RULE_MATCH, 'match' => 'newPassword']
            ]
        ];
    }

    public function labels(): array {
        return [
            'password' => 'Current password',
            'newPassword' => 'New password',
            'newPasswordConfirm' => 'Confirm new password'
        ];
    }

    public function validate(): bool {
        if ($this->newPassword === $this->password) {
            $this->addCustomError('newPassword', 'Your new password cannot match your current password.');
        }

        return parent::validate();
    }

    public function apply() {
        $user = Application::$app->user;

        if (!password_verify($this->password, $user->password)) {
            return false;
        }

        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->newPassword = password_hash($this->newPassword, PASSWORD_BCRYPT);

        $user->password = $this->newPassword;

        return $user->update(['password']);
    }

}