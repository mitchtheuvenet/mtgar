<?php

use app\core\Migration;

class m0003_cards extends Migration {

    public function up() {
        self::exec(<<<'SQL'
            CREATE TABLE `cards` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(32) NOT NULL,
                `type` VARCHAR(16) NOT NULL,
                `rarity` CHAR(1) NOT NULL,
                `set` CHAR(3) NOT NULL,
                `number` SMALLINT NOT NULL,
                `multiverseid` INT UNIQUE NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;
        SQL);
    }

    public function down() {
        self::exec(<<<'SQL'
            DROP TABLE `cards`;
        SQL);
    }

}