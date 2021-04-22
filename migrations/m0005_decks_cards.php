<?php

use app\core\Migration;

class m0005_decks_cards extends Migration {

    public function up() {
        self::exec(<<<'SQL'
            CREATE TABLE `decks_cards` (
                `deck` INT NOT NULL,
                `card` INT NOT NULL,
                `amount` TINYINT NOT NULL,

                PRIMARY KEY (`deck`, `card`),

                FOREIGN KEY (`deck`)
                    REFERENCES `decks`(`id`)
                    ON DELETE CASCADE,
                FOREIGN KEY (`card`)
                    REFERENCES `cards`(`id`)
                    ON DELETE CASCADE
            ) ENGINE=INNODB;
        SQL);
    }

    public function down() {
        self::exec(<<<'SQL'
            DROP TABLE `decks_cards`;
        SQL);
    }

}