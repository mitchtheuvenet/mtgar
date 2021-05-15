<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class DbCard extends DbModel {

    public int $multiverseid;
    public string $name;
    public string $type;

    public ?int $id;
    public ?string $created_at;

    public static function tableName(): string {
        return 'cards';
    }

    public static function columnNames(): array {
        return ['name', 'type', 'multiverseid'];
    }

    public static function primaryKey(): string {
        return 'id';
    }

    public static function queryLimit(): int {
        return 100;
    }

    public function rules(): array {
        return [];
    }

    public function validate(): bool {
        $card = \mtgsdk\Card::find($this->multiverseid);

        if (!empty($card)) {
            $this->name = $card->name;
            $this->type = in_array('Creature', $card->types) ? 'Creature' : $card->types[0];

            return true;
        }

        return false;
    }

}