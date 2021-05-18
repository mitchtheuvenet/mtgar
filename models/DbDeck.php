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
            'display_id' => [
                [
                    self::RULE_PATTERN,
                    'pattern' => '/[a-zA-Z0-9\-_]{' . self::DISPLAY_ID_LENGTH . '}/',
                    'description' => 'a deck ID'
                ],
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

    public function addCard(int $cardId, int $amount = 1): bool {
        $statement = null;

        if ($this->containsCard($cardId)) {
            $statement = self::prepare("
                UPDATE `decks_cards`
                SET `amount` = `amount` + :amount
                WHERE `deck` = :deck
                AND `card` = :card;
            ");
        } else {
            $statement = self::prepare("
                INSERT INTO `decks_cards` (`deck`, `card`, `amount`)
                VALUES (:deck, :card, :amount);
            ");
        }

        $statement->bindValue(':deck', $this->id);
        $statement->bindValue(':card', $cardId);
        $statement->bindValue(':amount', $amount);

        try {
            $statement->execute();

            return true;
        } catch (\PDOException $e) {
            // TODO: add exception handling

            return false;
        }
    }

    public function removeCard(int $cardId, $isCommander = false) {
        if ($this->containsCard($cardId, $isCommander)) {
            $table = $isCommander ? '`decks_commanders`' : '`decks_cards`';
            $cardColumn = $isCommander ? '`commander`' : '`card`';

            $statement = self::prepare("
                DELETE FROM {$table}
                WHERE `deck` = :deck
                AND {$cardColumn} = :c;
            ");

            $statement->bindValue(':deck', $this->id);
            $statement->bindValue(':c', $cardId);

            try {
                $statement->execute();

                return true;
            } catch (\PDOException $e) {
                // TODO: add exception handling
            }
        }

        return false;
    }

    public function addCommander(int $cardId): bool {
        $statement = self::prepare("
            INSERT INTO `decks_commanders` (`deck`, `commander`)
            VALUES (:deck, :commander);
        ");

        $statement->bindValue(':deck', $this->id);
        $statement->bindValue(':commander', $cardId);

        try {
            $statement->execute();

            return true;
        } catch (\PDOException $e) {
            // TODO: add exception handling

            return false;
        }
    }

    public static function findCards(int $deckId, bool $findCommanders = false) {
        $table = $findCommanders ? '`decks_commanders`' : '`decks_cards`';
        $cardColumn = $findCommanders ? '`commander`' : '`card`';

        $selectAmount = !$findCommanders ? 'dc.`amount`, ' : '';

        $statement = self::prepare("
            SELECT dc.{$cardColumn}, {$selectAmount}c.`name`, c.`type`, c.`multiverseid`
            FROM {$table} AS dc
            INNER JOIN `cards` AS c
            ON c.`id` = dc.{$cardColumn}
            INNER JOIN `decks` AS d
            ON d.`id` = dc.`deck`
            WHERE d.`id` = :id;
        ");

        $statement->bindValue(':id', $deckId);

        try {
            $statement->execute();

            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // TODO: add exception handling

            return false;
        }
    }

    public function containsCard(int $cardId, bool $asCommander = false) {
        $cardCount = $this->countCard($cardId, $asCommander);

        return intval($cardCount) > 0;
    }

    public static function formColors(): array {
        return ['colorW', 'colorU', 'colorB', 'colorR', 'colorG'];
    }

    public static function validateDisplayId(string $id): bool {
        return preg_match('/[a-zA-Z0-9\-_]{' . self::DISPLAY_ID_LENGTH . '}/', $id);
    }

    private function countCard(int $cardId, bool $findCommander) {
        $table = $findCommander ? '`decks_commanders`' : '`decks_cards`';
        $cardColumn = $findCommander ? 'commander' : 'card';

        $statement = self::prepare("
            SELECT COUNT(*)
            FROM {$table}
            WHERE `deck` = :deck
            AND `{$cardColumn}` = :{$cardColumn};
        ");

        $statement->bindValue(":deck", $this->id);
        $statement->bindValue(":{$cardColumn}", $cardId);

        try {
            $statement->execute();

            return $statement->fetchColumn();
        } catch (\PDOException $e) {
            // TODO: add exception handling

            return false;
        }
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