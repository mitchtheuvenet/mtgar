<?php

namespace app\models;

use app\core\DbModel;

class DbUser extends DbModel {

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public int $status = self::STATUS_INACTIVE;

    public string $username = '';
    public string $password = '';
    public string $passwordConfirm = '';
    public string $email = '';
    public string $emailConfirm = '';

    public static function tableName(): string {
        return 'users';
    }

    public static function columnNames(): array {
        return ['username', 'password', 'email', 'status'];
    }

    public static function primaryKey(): string {
        return 'id';
    }

    public function labels(): array {
        return [
            'username' => 'Username',
            'password' => 'Password',
            'passwordConfirm' => 'Confirm password',
            'email' => 'E-mail address',
            'emailConfirm' => 'Confirm e-mail address'
        ];
    }

    public function save() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $this->status = self::STATUS_ACTIVE;

        return parent::save();
    }

    public function rules(): array {
        return [
            'username' => [
                self::RULE_REQUIRED,
                [self::RULE_PATTERN, 'pattern' => '/[a-zA-Z0-9]{4,16}/'],
                [self::RULE_UNIQUE, 'class' => self::class]
            ],
            'password' => [
                self::RULE_REQUIRED,
                [self::RULE_MIN, 'min' => 8]
            ],
            'passwordConfirm' => [
                self::RULE_REQUIRED,
                [self::RULE_MATCH, 'match' => 'password']
            ],
            'email' => [
                self::RULE_REQUIRED,
                self::RULE_EMAIL,
                [self::RULE_MAX, 'max' => 255],
                [self::RULE_UNIQUE, 'class' => self::class]
            ],
            'emailConfirm' => [
                self::RULE_REQUIRED,
                [self::RULE_MATCH, 'match' => 'email']
            ]
        ];
    }

}