<?php

use app\core\Migration;

class m0004_decks extends Migration {

    public function up() {
        self::exec("
            CREATE TABLE `decks` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user` INT NOT NULL,
                `name` VARCHAR(32) NOT NULL,
                `description` VARCHAR(128) NOT NULL,
                `colors` VARCHAR(5) NOT NULL,
                `commander` INT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                FOREIGN KEY (`user`)
                    REFERENCES `users`(`id`)
                    ON DELETE CASCADE,
                FOREIGN KEY (`commander`)
                    REFERENCES `cards`(`id`)
                    ON DELETE SET NULL
            ) ENGINE=INNODB;
        ");
    }

    public function down() {
        self::exec("DROP TABLE `decks`;");
    }

}