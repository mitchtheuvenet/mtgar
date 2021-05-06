<?php

use app\core\Migration;

class m0005_decks_commanders extends Migration {

    public function up() {
        self::exec(<<<'SQL'
            CREATE TABLE `decks_commanders` (
                `deck` INT NOT NULL,
                `commander` INT NOT NULL,

                PRIMARY KEY (`deck`, `commander`),

                FOREIGN KEY (`deck`)
                    REFERENCES `decks`(`id`)
                    ON DELETE CASCADE,
                FOREIGN KEY (`commander`)
                    REFERENCES `cards`(`id`)
                    ON DELETE CASCADE
            ) ENGINE=INNODB;
        SQL);
    }

    public function down() {
        self::exec(<<<'SQL'
            DROP TABLE `decks_commanders`;
        SQL);
    }

}