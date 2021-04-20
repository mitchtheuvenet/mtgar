<?php

// cards

// -id INT
// -name VARCHAR(32)
// -type VARCHAR(16)
// -rarity VARCHAR(8)
// -set CHAR(3)
// -number SMALLINT
// -multiverseid INT
// -created_at TIMESTAMP

use app\core\Migration;

class m0003_cards extends Migration {

    public function up() {
        self::exec("
            CREATE TABLE `cards` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(32) NOT NULL,
                `type` VARCHAR(16) NOT NULL,
                `rarity` CHAR(1) NOT NULL,
                `set` CHAR(3) NOT NULL,
                `number` SMALLINT NOT NULL,
                `multiverseid` INT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;
        ");
    }

    public function down() {
        self::exec("DROP TABLE `cards`;");
    }

}