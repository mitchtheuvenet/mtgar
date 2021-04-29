<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

use app\utils\Mana;

class DbDeck extends DbModel {

    private const DISPLAY_ID_LENGTH = 8;
    private const DISPLAY_ID_CHARACTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';

    public string $display_id;

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

    public static function tableName(): string {
        return 'decks';
    }

    public static function columnNames(): array {
        return ['display_id', 'user', 'title', 'description', 'colors'];
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
                [self::RULE_EXISTS, 'class' => self::class]
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

    public function save(): bool {
        $this->user = Application::$app->user->id;

        $this->display_id = self::generateDisplayId();

        return parent::save();
    }

    public static function formColors(): array {
        return ['colorW', 'colorU', 'colorB', 'colorR', 'colorG'];
    }

    private static function generateDisplayId() {
        $existingDisplayIds = self::findArray([], ['display_id'], self::BYPASS_QUERY_LIMIT);
        
        for ($i = 0; $i < count($existingDisplayIds); $i++) {
            $existingDisplayIds[$i] = $existingDisplayIds[$i]['display_id'];
        }

        $displayId = '';

        $displayIdCharArray = str_split(self::DISPLAY_ID_CHARACTERS);

        do {
            for ($i = 0; $i < self::DISPLAY_ID_LENGTH; $i++) {
                $displayId .= $displayIdCharArray[random_int(0, count($displayIdCharArray) - 1)];
            }
        } while (in_array($displayId, $existingDisplayIds));

        return $displayId;
    }

}