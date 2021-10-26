<?php

use app\core\Migration;

class m0007_donations extends Migration {

    public function up() {
        self::exec(<<<'SQL'
            CREATE TABLE `donations` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `amount` VARCHAR(16) NOT NULL,
                `status` TINYINT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;
        SQL);
    }

    public function down() {
        self::exec(<<<'SQL'
            DROP TABLE `donations`;
        SQL);
    }

}