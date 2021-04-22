<?php

use app\core\Migration;

class m0000_init extends Migration {

    public function up() {
        self::exec(<<<'SQL'
            CREATE TABLE `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `username` VARCHAR(16) NOT NULL,
                `password` CHAR(60) NOT NULL,
                `email` VARCHAR(255) NOT NULL,
                `status` TINYINT NOT NULL,
                `admin` BIT DEFAULT 0 NOT NULL
            ) ENGINE=INNODB;
        SQL);
    }

    public function down() {
        self::exec(<<<'SQL'
            DROP TABLE `users`;
        SQL);
    }

}