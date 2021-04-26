<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

use app\utils\Mana;

class DbDeck extends DbModel {

    public int $user;

    public string $title = '';
    public string $description = '';

    public string $colorW = '';
    public string $colorU = '';
    public string $colorB = '';
    public string $colorR = '';
    public string $colorG = '';

    public string $colors;

    public ?int $id;
    public ?int $commander;
    public ?string $created_at;

    public function __construct() {
        $this->user = Application::$app->user->id;
    }

    public static function tableName(): string {
        return 'decks';
    }

    public static function columnNames(): array {
        return ['user', 'title', 'description', 'colors'];
    }

    public static function primaryKey(): string {
        return 'id';
    }

    public static function queryLimit(): int {
        return 8;
    }

    public function labels(): array {
        return [
            'title' => 'Title',
            'description' => 'Description'
        ];
    }

    public function rules(): array {
        return [
            'id' => [
                [self::RULE_EXISTS, 'class' => $this::class]
            ],
            'title' => [
                self::RULE_REQUIRED,
                [self::RULE_MAX, 'max' => 48]
            ],
            'description' => [
                self::RULE_REQUIRED,
                [self::RULE_MAX, 'max' => 128]
            ],
            'colors' => [
                self::RULE_REQUIRED,
                [self::RULE_MAX, 'max' => 5]
            ]
        ];
    }

    public function validate(): bool {
        $this->colors = '';

        foreach (self::formColors() as $color) {
            if (!empty($this->{$color})) {
                switch ($color) {
                    case 'colorW':
                        $this->colors .= Mana::WHITE;

                        break;
                    case 'colorU':
                        $this->colors .= Mana::BLUE;

                        break;
                    case 'colorB':
                        $this->colors .= Mana::BLACK;

                        break;
                    case 'colorR':
                        $this->colors .= Mana::RED;

                        break;
                    case 'colorG':
                        $this->colors .= Mana::GREEN;
                }
            }
        }

        if (empty($this->colors)) {
            $this->colors = Mana::COLORLESS;
        }

        return parent::validate();
    }

    public function update(array $columns = []): bool {
        if (!empty($this->id)) {
            $ownedDeck = DbDeck::findObject(['id' => ['value' => $this->id], 'user' => ['value' => $this->user]]);

            if (!empty($ownedDeck)) {
                return parent::update($columns);
            }
        }

        return false;
    }

    public static function formColors(): array {
        return ['colorW', 'colorU', 'colorB', 'colorR', 'colorG'];
    }

}