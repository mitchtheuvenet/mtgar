<?php

use app\core\Migration;

class m0002_verifications extends Migration {

    public function up() {
        self::exec("
            CREATE TABLE `verifications` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user` INT NOT NULL,
                `type` TINYINT NOT NULL,
                `code` CHAR(32) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                FOREIGN KEY (`user`) REFERENCES `users`(`id`),
                CONSTRAINT `uq_verification` UNIQUE (`user`, `type`)
            ) ENGINE=INNODB;
        ");
    }

    public function down() {
        self::exec("DROP TABLE `verifications`;");
    }

}