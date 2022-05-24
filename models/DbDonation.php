<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class DbDonation extends DbModel {

    public const STATUS_PENDING = 0;
    public const STATUS_PAID = 1;
    public const STATUS_EXPIRED = 2;
    public const STATUS_FAILED = 3;
    public const STATUS_CANCELED = 4;

    public int $status = self::STATUS_PENDING;

    public string $amount = '';

    public ?int $id;
    public ?string $created_at;

    public static function tableName(): string {
        return 'donations';
    }

    public static function columnNames(): array {
        return ['amount', 'status'];
    }

    public static function primaryKey(): string {
        return 'id';
    }

    public function labels(): array {
        return [
            'amount' => 'Amount in &euro;'
        ];
    }

    public function rules(): array {
        return [
            'amount' => [
                self::RULE_REQUIRED,
                [
                    self::RULE_PATTERN,
                    'pattern' => '/^\d{1,5}(\.\d{2})?$/',
                    'description' => 'an amount between 0.01 and 50000.00'
                ]
            ]
        ];
    }

}